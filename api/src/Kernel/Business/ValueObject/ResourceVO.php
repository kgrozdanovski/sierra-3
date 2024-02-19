<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Communication\HttpFoundation\Request\CriteriaAwareTrait;

class ResourceVO extends AbstractVO
{
    use CriteriaAwareTrait;

    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setIdAsLoggedInUser(): void
    {
        $this->id = $this->getUserContext()->getId();
    }
}
