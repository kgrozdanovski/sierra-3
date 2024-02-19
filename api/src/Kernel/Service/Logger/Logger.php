<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//
//declare(strict_types=1);
//
namespace App\Kernel\Service\Logger;
class Logger {}
//
//use App\User\Persistence\Doctrine\Repository\UserRepository;
//use Monolog\Logger as MonologLogger;
//use Psr\Log\LoggerInterface;
//use Symfony\Bundle\SecurityBundle\Security;
//use Symfony\Component\HttpFoundation\Request;
//use App\Kernel\Symfony\Observability\LoggerContext;
//use Symfony\Component\Uid\Factory\UuidFactory;
//
//final class Logger implements LoggerInterface
//{
//    public function __construct(
//        private MonologLogger $logger,
//        private Security $security,
//        private UuidFactory $uuidFactory,
//        private UserRepository $userRepository
//    ) {}
//
//    public function addRecord(
//        int|Level $level,
//        string $message,
//        array $context = [],
//        DateTimeImmutable $datetime = null
//    ): bool {
//        $context = $this->buildContext($context);
//
//        return parent::addRecord($level, $message, $context, $datetime);
//    }
//
//    /**
//     * Enrich Monolog's context with our extended context.
//     *
//     * @param array $context
//     *
//     * @return array
//     */
//    private function buildContext(array $context): array
//    {
//        $securityUser = $this->security->getUser();
//        $user = $this->userRepository->findOneBy(['email' => $securityUser?->getUserIdentifier()]);
//
//        $uuid = $this->uuidFactory->create();
//
//        $requestContext = new LoggerContext(Request::createFromGlobals(), $user, $uuid);
//
//        return array_merge($context, $requestContext->toArray());
//    }
//}
