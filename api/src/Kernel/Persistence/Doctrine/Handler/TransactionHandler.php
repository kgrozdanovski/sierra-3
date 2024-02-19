<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\Handler;

use Doctrine\ORM\EntityManagerInterface;

final readonly class TransactionHandler implements TransactionHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * {@inheritDoc}
     */
    public function handleTransaction(\Closure $callback): mixed
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            $result = $callback();

            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return $result;
        } catch (\Exception|\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();

            throw $exception;
        }
    }
}
