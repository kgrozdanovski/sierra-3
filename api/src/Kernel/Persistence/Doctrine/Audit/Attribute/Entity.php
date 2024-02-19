<?php

namespace App\Kernel\Persistence\Doctrine\Audit\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Entity
{
    public function __construct(public string $name) {}
}
