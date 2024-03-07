<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation;

use App\Kernel\Communication\HttpKernel\Exception\BadRequestHttpException;
use JsonException;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestDataCollector
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @return string
     *
     * @throws \JsonException
     */
    public function collect(): string
    {
        $content = $this->getContent();
        $routeParams = $this->getRouteParams();

        $paramsCount = \count($keys = array_intersect_key($content, $routeParams));
        if ($paramsCount > 0) {
            throw new BadRequestHttpException(sprintf(
                'Parameters (%s) used as route attributes can not be used in the request body or query parameters.',
                implode(', ', array_keys($keys))
            ));
        }

        return json_encode(
            array_merge($content, $routeParams)
        );
    }

    /**
     * @return array
     *
     * @throws \JsonException
     */
    public function getContent(): array
    {
        $content = $this->requestStack->getCurrentRequest()->getContent();

        if ('' === $content) {
            return [];
        }

        try {
            return json_decode($content, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JsonException('Could not decode request body.', $e->getCode(), $e);
        }
    }

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('_route_params', []);
    }

    /**
     * @return array
     */
    public function getController(): array
    {
        $controllerFqdn = $this->requestStack->getCurrentRequest()->attributes->get('_controller');
        if (empty($controllerFqdn)) {
            return [];
        }

        return explode('::', $controllerFqdn);
    }

    /**
     * @return string
     */
    public function getControllerAction(): string
    {
        return $this->getController()[1] ?? 'Default';
    }
}
