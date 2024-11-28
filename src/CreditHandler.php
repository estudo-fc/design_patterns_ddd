<?php

namespace Maruko\DddPhp;

class CreditHandler implements Observer
{

    private string $operation = 'credit';

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

        $account->credit($command->getAmount());
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}