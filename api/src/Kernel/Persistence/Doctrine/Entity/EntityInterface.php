<?php

namespace App\Kernel\Persistence\Doctrine\Entity;

use App\Kernel\Business\PropertyAccess\ArrayObject\ArrayObjectInterface;

interface EntityInterface extends ArrayObjectInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;
}
