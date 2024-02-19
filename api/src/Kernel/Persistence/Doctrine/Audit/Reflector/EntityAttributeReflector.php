<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\Audit\Reflector;

use App\Kernel\Persistence\Doctrine\Audit\Attribute\Entity;

final class EntityAttributeReflector
{
    /**
     * @param mixed $entity
     * @return string|null
     */
    public function getName(mixed $entity): ?string
    {
        try {
            $reflectionClass = new \ReflectionClass($entity);
            $reflectionAttributes = $reflectionClass->getAttributes(Entity::class);
        } catch (\ReflectionException $exception) {
            return null;
        }

        if (!$reflectionAttributes) {
            return null;
        }

        $arguments = $reflectionAttributes[0]->getArguments();

        if (!array_key_exists('name', $arguments)) {
            return null;
        }

        return $arguments['name'];
    }
}
