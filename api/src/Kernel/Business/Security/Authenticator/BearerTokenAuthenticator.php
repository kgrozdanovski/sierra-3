<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\Security\Authenticator;

use App\Auth\Business\AuthFacade;
use App\Auth\Persistence\Doctrine\Entity\AccessToken;
use App\Kernel\Business\Security\Cryptography\Cryptography;
use App\Kernel\Business\Security\Token\BearerToken;
use App\Kernel\Communication\HttpFoundation\Response\ApiResponse;
use App\Kernel\Communication\HttpFoundation\Type\StatusCode;
use App\Auth\Persistence\Doctrine\Repository\AccessTokenRepository;
use App\User\Persistence\Doctrine\Entity\User;
use App\User\Persistence\Doctrine\Repository\UserRepository;
use Random\Engine\Secure;
use Random\Randomizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

readonly class BearerTokenAuthenticator implements AuthenticatorInterface
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
        private UserRepository $userRepository,
        private Cryptography $cryptography
    ) {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     *
     * @param Request $request
     *
     * @return bool|null
     */
    #[\Override]
    public function supports(Request $request): ?bool
    {
        // Skip the login route
        if ($request->attributes->get('_route') === 'auth_login') {
            return false;
        }
        return true;
    }

    #[\Override]
    public function authenticate(Request $request): Passport
    {
        // Fetch the token from the request headers
        $token = $request->headers->get('Authorization');
        if (empty($token)) {
            throw new CustomUserMessageAuthenticationException('No token provided.');
        }

        // Get the value after "Bearer "
        $tokenEncoded = substr($token, 7);

        // Decode the token
        $token = $this->cryptography->urlSafeBase64Decode($tokenEncoded);
        // Decrypt the token
        $token = $this->cryptography->decrypt($token);

        // Extract the user identifier (UUIDv4) from the token (first 36 digits)
        $userId = substr($token, 0, 36);
        // The rest of the token is the token base
        $tokenBase = substr($token, 36);

        // Get the user from the database
        $user = $this->userRepository->find($userId);

        // Verify the access token exists and fetch it
        $accessToken = $this->accessTokenRepository->findOneBy([
            'user' => $user->getId()
            //,'deleted' => false
        ], ['createdAt' => 'DESC']);

        if ($accessToken === null) {
            throw new CustomUserMessageAuthenticationException('Invalid token.');
        }
        // Verify the hash of the base matches the stored hash
        if (hash('sha3-224', $tokenBase) !== $accessToken->getToken()) {
            throw new CustomUserMessageAuthenticationException('Invalid token.');
        }
        // Verify the access token has not expired, unless it's a non-expiring token
        if (!is_null($accessToken->getValidUntil())) {
            if ($accessToken->getValidUntil()->getTimestamp() < time()) {
                throw new CustomUserMessageAuthenticationException('Token expired.');
            }
        }

        // Create a Symfony Passport with the user badge
        // Note: $userIdentifier is matched against whatever User property you define in the security.yaml User Provider
        $passport = new SelfValidatingPassport(new UserBadge($user->getEmail()), []);
        // Set custom attributes to the passport
        $passport->setAttribute('roles', $user->getRoles());
        // Set the token to the passport
        $passport->setAttribute('token', $tokenEncoded);

        return $passport;
    }

    /**
     * Create a Token object from the Passport object containing the authenticated user.
     *
     * Note: this is called after every successful authentication.
     *
     * @param Passport $passport
     * @param string $firewallName
     *
     * @return TokenInterface
     * @throws \Exception
     */
    #[\Override]
    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        // Get the user from the passport
        $user = $passport->getUser();
        // Get the full User entity from the user object
        // Note: $userIdentifier is matched against whatever User property you define in the security.yaml User Provider
        $userEntity = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        // Retrieve the token from the passport
        $token = $passport->getAttribute('token');

        $bearerToken = new BearerToken($token, $user->getRoles());
        $bearerToken->setUser($userEntity);

        // Return the token
        return $bearerToken;
    }

    #[\Override]
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        // Set the User object to the request
        $request->attributes->set('user', $token->getUser());

        // Continue with the next middleware/controller
        return null;
    }

    #[\Override]
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        // todo: Log authentication failure to the database access_log table

        $response = new ApiResponse();
        return $response
            ->setStatusCode(StatusCode::UNAUTHORIZED)
            ->setMessage('Authentication failed.')
            ->render();
    }

    /**
     * @param User $userEntity
     *
     * @return string
     * @throws \Exception
     */
    public function createRawToken(User $userEntity): string
    {
        // Generate a secure random number for the key base
        $secureRandomEngine = new Secure();
        $randomizer = new Randomizer($secureRandomEngine);
        $bytes = $randomizer->getBytes(12);

        // Convert the bytes to a readable hexadecimal string
        $tokenBase = bin2hex($bytes);   // this will be stored in the database
        // Form a token with the user's internal UUID and the hashed token
        $token = $this->cryptography->encrypt($userEntity->getId() . $tokenBase);
        // Return a URL-safe base64 encoded hash
        $token = $this->cryptography->urlSafeBase64Encode($token);

        // Soft-delete all previous access tokens for this user
        //$this->accessTokenRepository->softDeleteBy(['user_id' => $userEntity->getId()]);

        $validUntil = \DateTimeImmutable::createFromMutable(new \DateTime('now +1 hour'));

        // Generate SHA-3 hash of the token base for secure storage
        $hashedTokenBase = hash('sha3-224', $tokenBase);

        // Save the non-hashed token base to the database with the configured expiration time
        $accessToken = (new AccessToken())
            ->setUser($userEntity)
            ->setToken($hashedTokenBase)
            ->setValidUntil($validUntil);
        $this->accessTokenRepository->save($accessToken, true);

        return $token;
    }
}
