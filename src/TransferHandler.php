<?php

namespace Maruko\DddPhp;

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

        $accountFrom->debit($command->getAmount());
        $accountTo->credit($command->getAmount());
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}