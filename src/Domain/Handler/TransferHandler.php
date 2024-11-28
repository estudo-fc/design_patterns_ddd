<?php

namespace Maruko\DddPhp\Domain\Handler;

use Maruko\DddPhp\Application\Command\Command;
use Maruko\DddPhp\Domain\Service\TransferService;
use Maruko\DddPhp\Infra\Queue\Observer;
use Maruko\DddPhp\Infra\Repository\AccountRepositoryMemory;

class TransferHandler implements Observer
{

    private string $operation = 'transfer';

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
        $accountFrom = $this->accountRepository->get($command->getAccountFromDocument());
        $accountTo = $this->accountRepository->get($command->getAccountToDocument());

        $transferService = new TransferService();
        $transferService->transfer($accountFrom, $accountTo, $command->getAmount());
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}