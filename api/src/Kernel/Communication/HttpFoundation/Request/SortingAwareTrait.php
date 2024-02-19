<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Communication\HttpFoundation\Request;

use App\Kernel\Business\ValueObject\Sorting;
use App\Kernel\Communication\HttpFoundation\Request\Builder\SortingBuilder;

trait SortingAwareTrait
{
    private ?Sorting $sorting = null;

    private readonly SortingBuilder $sortingBuilder;

    /**
     * @return Sorting
     */
    public function getSorting(): Sorting
    {
        if ($this->sorting === null) {
            $this->sorting = $this->buildSorting();
        }

        return $this->sorting;
    }

    /**
     * @param Sorting $sorting
     *
     * @return $this
     */
    public function setSorting(Sorting $sorting): static
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * @return Sorting
     */
    protected function buildSorting(): Sorting
    {
        return $this->getSortingBuilder()->build();
    }

    /**
     * @return SortingBuilder
     */
    public function getSortingBuilder(): SortingBuilder
    {
        return $this->sortingBuilder;
    }

    /**
     * @param SortingBuilder $sortingBuilder
     *
     * @return void
     */
    public function setSortingBuilder(SortingBuilder $sortingBuilder): void
    {
        $this->sortingBuilder = $sortingBuilder;
    }
}
