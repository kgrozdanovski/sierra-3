<?php

namespace App\Kernel\Persistence\Doctrine\Handler;

use Doctrine\DBAL\Exception;

interface TransactionHandlerInterface
{
    /**
     * @param \Closure $callback
     *
     * @throws Exception
     * @return mixed
     */
    public function handleTransaction(\Closure $callback): mixed;
}
