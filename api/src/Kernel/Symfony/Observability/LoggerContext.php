<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Symfony\Observability;

use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\Request;
use App\Kernel\Business\PropertyAccess\ArrayObject\ArrayObjectInterface;
use App\Kernel\Business\PropertyAccess\ArrayObject\ArrayObjectTrait;
use App\User\Persistence\Doctrine\Entity\User;
use Symfony\Component\Uid\Uuid;

/**
 * Extracts data from request object suitable for logging.
 */
class LoggerContext implements ArrayObjectInterface
{
    use ArrayObjectTrait;

    private string $requestId;

    private string $httpRoute;

    private string $httpMethod;

    private string $clientIpAddress;

    private string $userId;

    private array $userRoles;

    public function __construct(Request $request, User $user, Uuid $uuid)
    {
        $this->setRequestId($uuid->__toString());
        $this->setHttpRoute($request->getPathInfo());
        $this->setHttpMethod($request->getMethod());
        $this->setClientIpAddress($request->getClientIp());

        $this->setUserId($user->getId());
        $this->setUserRoles($user->getRoles());
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     * @return LoggerContext
     */
    public function setRequestId(string $requestId): LoggerContext
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpRoute(): string
    {
        return $this->httpRoute;
    }

    /**
     * @param string $httpRoute
     * @return LoggerContext
     */
    public function setHttpRoute(string $httpRoute): LoggerContext
    {
        $this->httpRoute = $httpRoute;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @param string $httpMethod
     * @return LoggerContext
     */
    public function setHttpMethod(string $httpMethod): LoggerContext
    {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientIpAddress(): string
    {
        return $this->clientIpAddress;
    }

    /**
     * @param string $clientIpAddress
     * @return LoggerContext
     */
    public function setClientIpAddress(string $clientIpAddress): LoggerContext
    {
        $this->clientIpAddress = $clientIpAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return LoggerContext
     */
    public function setUserId(string $userId): LoggerContext
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return array
     */
    public function getUserRoles(): array
    {
        return $this->userRoles;
    }

    /**
     * @param array $userRoles
     * @return LoggerContext
     */
    public function setUserRoles(array $userRoles): LoggerContext
    {
        $this->userRoles = $userRoles;
        return $this;
    }
}
