<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Communication\HttpFoundation\Request\PaginationAwareTrait;
use App\Kernel\Communication\HttpFoundation\Request\SortingAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionResponseVO extends AbstractResponseVO
{
    use PaginationAwareTrait;
    use SortingAwareTrait;

    public const PAGINATION = 'pagination';
    public const SORTING = 'sorting';

    /**
     * {@inheritDoc}
     */
    public function render(string $action = '', array $parameters = [], JsonResponse $response = null): JsonResponse
    {
        if ($response !== null) {
            return $response;
        }

        $data = match ($action) {
            'create' => [
                self::DATA => 'Successfully created the entity collection.',
            ],
            'update' => [
                self::DATA => 'Successfully updated the entity collection.',
            ],
            'delete' => [
                self::DATA => 'Successfully deleted the entity collection.'
            ],
            default => $this->prepareReadData()
        };

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @return array
     */
    protected function prepareReadData(): array
    {
        $this->getPagination()->setTotal($this->getData()->count());

        return [
            self::DATA => $this->getNormalizedData(),
            self::PAGINATION => [
                'current_page'   => $this->getPagination()->getCurrentPage(),
                'requested_page' => $this->getPagination()->getPage(),
                'last_page'      => $this->getPagination()->getLastPage(),
                'limit'          => $this->getPagination()->getLimit(),
                'from'           => $this->getPagination()->getFrom(),
                'to'             => $this->getPagination()->getTo(),
                'total'          => $this->getPagination()->getTotal(),
            ],
            self::SORTING => [
                'sort_by' => $this->getSorting()->getSortBy(),
                'sort_dir' => $this->getSorting()->getSortDir(),
            ]
        ];
    }
}
