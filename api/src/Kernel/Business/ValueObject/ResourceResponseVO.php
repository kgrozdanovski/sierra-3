<?php

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Communication\HttpFoundation\Response\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResourceResponseVO extends AbstractResponseVO
{
    public const RESOURCE_ID = 'id';

    /**
     * {@inheritDoc}
     */
    public function render(string $action = '', array $parameters = []): JsonResponse
    {
        $response = new ApiResponse();

        switch ($action) {
            case 'create':
                $response
                    ->setMessage('Successfully created new entity.')
                    ->setData([
                        self::RESOURCE_ID => $this->getData()->getId()
                    ]);
                break;
            case 'update':
                $response
                    ->setMessage('Successfully updated entity.')
                    ->setData([
                        self::RESOURCE_ID => $this->getData()->getId()
                    ]);
                break;
            case 'delete':
                $response
                    ->setMessage('Successfully deleted entity.')
                    ->setData([
                        self::RESOURCE_ID => $this->getData()->getId()
                    ]);
                break;
            default:
                $response->setData($this->getNormalizedData());
        };

        return $response->render();
    }
}
