<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\PropertyAccess\ArrayObject;

use App\Kernel\Business\PropertyAccess\Attribute\Ignore;

trait ArrayObjectTrait
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $key => $value) {
            try {
                $reflectionProperty = new \ReflectionProperty($this, $key);

                // Skip properties flagged as ignored
                if (!$reflectionProperty->getAttributes(Ignore::class)) {
                    $result[$key] = $value;
                }
            } catch (\ReflectionException $exception) {}
        }

        return $result;
    }

    /**
     * @param array $properties
     *
     * @return void
     */
    public function fromArray(array $properties): void
    {
        foreach ($properties as $key => $value) {
            $setterMethod = 'set'. ucfirst($key);

            if (method_exists($this, $setterMethod)) {
                $this->$setterMethod($value);
            }
        }
    }
}
