<?php

namespace Maruko\DddPhp\Domain\Handler;

use Maruko\DddPhp\Application\Command\Command;
use Maruko\DddPhp\Infra\Queue\Observer;
use Maruko\DddPhp\Infra\Repository\AccountRepositoryMemory;

class DebitHandler implements Observer
{

    private string $operation = 'debit';

    public function __construct(
        private readonly AccountRepositoryMemory $accountRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function notify(Command $command): void
    {
        $account = $this->accountRepository->get($command->getAccountDocument());

        $account->debit($command->getAmount());
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}