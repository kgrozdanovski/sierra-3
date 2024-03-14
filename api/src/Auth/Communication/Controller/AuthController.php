<?php

namespace App\Auth\Communication\Controller;

use App\Auth\Business\AuthFacade;
use App\Auth\Persistence\Doctrine\Repository\AccessTokenRepository;
use App\Kernel\Business\Security\Authenticator\BearerTokenAuthenticator;
use App\Kernel\Business\ValueObject\CollectionVO;
use App\Kernel\Business\ValueObject\ResourceVO;
use App\Kernel\Communication\HttpFoundation\Response\ApiResponse;
use App\Kernel\Communication\HttpFoundation\Type\StatusCode;
use App\User\Business\ValueObject\UserVO;
use App\User\Persistence\Doctrine\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Kernel\Communication\Controller\ControllerInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AuthController extends AbstractController implements ControllerInterface
{
    public function __construct(
        private readonly BearerTokenAuthenticator $authenticator
    ) {
    }

    #[Route('/api/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user, Request $request): JsonResponse
    {
        $response = new ApiResponse();

        // Make sure a User was found with those credentials
        // Note: credential properties are configured in security.yaml
        if ($user === null) {
            return $response
                ->setMessage('Invalid credentials.')
                ->setStatusCode(StatusCode::UNAUTHORIZED)
                ->render();
        }

        // Fetch a token from the authenticator
        $accessToken = $this->authenticator->createRawToken($user);

        return $response
            ->setMessage('Successfully logged in.')
            ->setData([
                'user_id' => $user->getId(),
                'token' => $accessToken
            ])
            ->render();
    }

    #[Route('/api/auth/logout', name: 'auth_logout', methods: ['GET'])]
    public function logout(#[CurrentUser] ?User $user): JsonResponse
    {
        $response = new ApiResponse();
        return $response
            ->setMessage('Successfully logged out.')
            ->render();
    }
}
