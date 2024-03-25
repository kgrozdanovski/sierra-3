<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\Generator;

use Doctrine\ORM\EntityManagerInterface;

interface CustomGeneratorInterface
{
    /**
     * Generates a value for an entity property.
     */
    public function generateValue(EntityManagerInterface $em, object|null $entity): mixed;
}