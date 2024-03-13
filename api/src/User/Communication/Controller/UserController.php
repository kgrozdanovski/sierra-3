<?php

namespace App\User\Communication\Controller;

use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceVO;
use App\User\Business\UserFacade;
use App\User\Business\ValueObject\UserVO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Kernel\Communication\Controller\ControllerInterface;
use Symfony\Component\Routing\Requirement\Requirement;

class UserController extends AbstractController implements ControllerInterface
{
    public function __construct(
        private readonly UserFacade $facade
    ) {
    }

    #[Route('/api/user/register', methods: ['POST'])]
    public function register(UserVO $userVO): JsonResponse
    {
        return $this->facade
            ->createResource($userVO)
            ->render();
    }

    #[Route('/api/user', methods: ['POST'])]
    public function create(UserVO $userVO): JsonResponse
    {
        return $this->facade
            ->createResource($userVO)
            ->render();
    }

    #[Route(
        '/api/user/{public_id}',
        requirements: ['public_id' => Requirement::POSITIVE_INT],
        methods: ['GET']
    )]
    public function resource(ResourceVO $resourceVO): JsonResponse
    {
        return $this->facade
            ->readResource($resourceVO)
            ->render();
    }

    #[Route('/api/user', methods: ['GET'])]
    public function collection(CollectionVO $collectionVO): JsonResponse
    {
        return $this->facade
            ->readCollection($collectionVO)
            ->render();
    }

    #[Route(
        '/api/user/{public_id}',
        requirements: ['public_id' => Requirement::POSITIVE_INT],
        methods: ['PATCH']
    )]
    public function update(UserVO $userVO): JsonResponse
    {
        return $this->facade
            ->updateResource($userVO)
            ->render();
    }

    #[Route(
        '/api/user/{id}',
        requirements: ['id' => Requirement::UUID_V4],
        methods: ['DELETE']
    )]
    public function delete(UserVO $userVO): JsonResponse
    {
        return $this->facade
            ->deleteResource($userVO)
            ->render();
    }
}
