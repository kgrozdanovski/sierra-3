<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

readonly class ValueObjectSubscriber
{
    public function __construct(
        private iterable $resolvers
    ) {}

    /**
     * @param ValueObjectInterface $valueObject
     *
     * @return ValueObjectInterface
     */
    public function subscribe(ValueObjectInterface $valueObject): ValueObjectInterface
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->supports($valueObject)) {
                $resolver->resolve($valueObject);
            }
        }

        foreach (get_object_vars($valueObject) as $objectVar) {
            if ($objectVar instanceof ValueObjectInterface) {
                $this->subscribe($objectVar);
            }
        }

        return $valueObject;
    }
}
