<?php

namespace App\Auth\Business\Event\Handler;

use App\Kernel\Communication\HttpFoundation\Response\ApiResponse;
use App\Kernel\Communication\HttpFoundation\Type\StatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

final class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * This is called when an interactive authentication attempt fails.
     *
     * @param mixed $request
     * @param mixed $exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure($request, $exception): JsonResponse
    {
        $response = new ApiResponse();
        return $response
            ->setStatusCode(StatusCode::UNAUTHORIZED)
            ->setMessage('Login failed.')
            ->render();
    }
}
