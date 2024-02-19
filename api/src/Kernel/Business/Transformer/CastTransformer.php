<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\Transformer;

use App\Kernel\Persistence\Doctrine\Entity\EntityInterface;

class CastTransformer
{
    /**
     * @param mixed $value
     *
     * @return string|null
     */
    public function toString(mixed $value): ?string
    {
        return match (gettype($value)) {
            'boolean', 'integer', 'double', 'array' => json_encode($value),
            'string' => $value,
            'object' => $this->objectToString($value),
            'NULL' => 'null'
        };
    }

    /**
     * @param mixed $value
     *
     * @return string|null
     */
    public function objectToString(mixed $value): ?string
    {
        return match (true) {
            $value instanceof \DateTimeInterface => $value->format('Y-m-d H:i:s'),
            $value instanceof \UnitEnum => is_string($value->value) ? $value->value : json_encode($value->value),
            $value instanceof EntityInterface => json_encode($value->getId()),
            default => null,
        };
    }
}
