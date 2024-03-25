<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Business\Serializer\Context\Builder\EntityContextBuilder;
use App\Kernel\Business\Serializer\Normalizer\Builder\EntityNormalizerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractResponseVO implements ValueObjectInterface
{
    public const DATA = 'data';

    private mixed $data = null;

    private array $serializerGroups = [];

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getNormalizedData(): array
    {
        try {
            return $this->getSerializer()->normalize($this->getData(), null, $this->getSerializerContext());
        } catch (ExceptionInterface $exception) {
            return [];
        }
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return new Serializer(
            $this->getSerializerNormalizers()
        );
    }

    /**
     * @return array
     */
    public function getSerializerGroups(): array
    {
        return $this->serializerGroups;
    }

    /**
     * @param array $serializerGroups
     *
     * @return $this
     */
    public function setSerializerGroups(array $serializerGroups): self
    {
        $this->serializerGroups = $serializerGroups;

        return $this;
    }

    /**
     * @return array
     */
    public function getSerializerContext(): array
    {
        return (new EntityContextBuilder())
            ->withDeepObjectToPopulate(true)
            ->withCircularReferenceHandler(function ($object) {
                return $this->getDefaultProperty($object);
            })
            ->withMaxDepthHandler(function ($attributeValue) {
                return match (gettype($attributeValue)) {
                    'object' => $this->getDefaultProperty($attributeValue),
                    'array' => null,
                    default => $attributeValue
                };
            })
            ->withMaxTreeDepthHandler(function ($object) {
                return $this->getDefaultProperty($object);
            })
            ->withDateTimeFormat('Y-m-d H:i:s') // todo: move to class constant
            ->withCircularReferenceLimit(1)
            ->withExtraAttributes(true)
            ->withGroups($this->getSerializerGroups())
            ->withNullValues(false)
            ->withMaxDepth(true)
            ->withMaxTreeDepth(true)
            ->withTreeDepthLimit(2)
            ->toArray();
    }

    /**
     * @return array
     */
    public function getSerializerNormalizers(): array
    {
        return (new EntityNormalizerBuilder())
            ->withDateTimeNormalizer()
            ->withBackedEnumNormalizer()
            ->withObjectNormalizer()
            ->toArray();
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request();
    }

    /**
     * @param string $action
     * @param array $parameters
     * @return JsonResponse
     */
    abstract public function render(string $action = '', array $parameters = []): JsonResponse;

    /**
     * Returns the default property for the given object.
     *
     * For use with circular reference or max depth handlers.
     *
     * @param object $object
     *
     * @return array|null
     */
    private function getDefaultProperty(object $object): ?array
    {
        // Prefer 'public_id' over 'id' for public facing entities
        if (method_exists($object, 'getPublicId')) {
            return ['public_id' => $object->getPublicId()];
        } else {
            return method_exists($object, 'getId') ? ['id' => $object->getId()] : null;
        }
    }
}
