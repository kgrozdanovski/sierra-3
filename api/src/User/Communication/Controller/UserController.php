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

    // todo: move regexp to new abstract controller parent class constant
    #[Route(
        '/api/user/{id}',
        requirements: ['id' => '[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(8|9|a|b)[a-f0-9]{3}\-[a-f0-9]{12}'],
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
}
