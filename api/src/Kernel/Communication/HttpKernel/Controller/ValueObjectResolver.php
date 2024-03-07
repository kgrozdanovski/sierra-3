<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpKernel\Controller;

use App\Kernel\Business\ValueObject\ValueObjectInterface;
use App\Kernel\Business\ValueObject\ValueObjectSerializer;
use App\Kernel\Business\ValueObject\ValueObjectSubscriber;
use App\Kernel\Business\ValueObject\ValueObjectValidator;
use App\Kernel\Communication\HttpFoundation\RequestDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ValueObjectResolver implements ValueResolverInterface
{
    public function __construct(
        private RequestDataCollector $requestDataCollector,
        private ValueObjectSerializer $valueObjectSerializer,
        private ValueObjectSubscriber $valueObjectSubscriber,
        private ValueObjectValidator $valueObjectValidator
    ) {
    }

    /**
     * Whether this resolver can resolve the value for the given ArgumentMetadata.
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() && is_a($argument->getType(), ValueObjectInterface::class, true);
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getType() || !is_a($argument->getType(), ValueObjectInterface::class, true)) {
            return [];
        }

        $valueObject = $this->valueObjectSerializer->deserialize(
            $this->requestDataCollector->collect(),
            $argument->getType()
        );
        $valueObject = $this->valueObjectSubscriber->subscribe($valueObject);
        $valueObject = $this->valueObjectValidator->validate(
            $valueObject,
            null,
            [$this->requestDataCollector->getControllerAction()]
        );

        return [$valueObject];
    }
}
