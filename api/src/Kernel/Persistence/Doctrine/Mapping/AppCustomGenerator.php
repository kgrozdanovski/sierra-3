<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Kernel\Persistence\Doctrine\Mapping;

use App\Kernel\Persistence\Doctrine\Generator\CustomGeneratorInterface;
use Attribute;
use Doctrine\ORM\Mapping\MappingAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class AppCustomGenerator implements MappingAttribute
{
    public function __construct(
        public readonly string|null $class = null,
    ) {
        // Check if class implements CustomGeneratorInterface
        if (!is_subclass_of($this->class, CustomGeneratorInterface::class)) {
            throw new \InvalidArgumentException('Class must implement CustomGeneratorInterface');
        }
    }
}
