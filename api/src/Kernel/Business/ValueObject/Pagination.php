<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

final class Pagination
{
    public const int DEFAULT_PAGE = 1;

    public const int DEFAULT_LIMIT = 15;

    private int $page;

    private int $limit;

    private int $total;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return $this
     */
    public function setTotal(int $total): self
    {
        // todo: revise the Exception
        if ($total < 0) {
            throw new \DomainException("total must be >= 0");
        }

        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        if ($this->total === 0 || $this->page > $this->getLastPage()) {
            return 0;
        }

        return $this->page;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        if ($this->limit === 0) {
            return 1;
        }

        return ceil($this->total / $this->limit);
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        if ($this->getCurrentPage() === 0) {
            return 0;
        }

        return $this->getOffset() + 1;
    }

    /**
     * @return int
     */
    public function getTo(): int
    {
        if ($this->limit === 0) {
            return $this->total;
        }

        return min($this->total, $this->getOffset() + $this->limit);
    }
}
