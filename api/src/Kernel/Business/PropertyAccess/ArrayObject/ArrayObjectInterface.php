<?php

namespace App\Kernel\Business\PropertyAccess\ArrayObject;

interface ArrayObjectInterface
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param array $properties
     *
     * @return void
     */
    public function fromArray(array $properties): void;

}