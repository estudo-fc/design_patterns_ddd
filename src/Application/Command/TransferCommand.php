<?php

namespace Maruko\DddPhp\Application\Command;

class TransferCommand implements Command
{
    private string $operation = 'transfer';

    public function __construct(
        private readonly string $accountFromDocument,
        private readonly string $accountToDocument,
        private readonly int     $amount
    )
    {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAccountFromDocument(): string
    {
        return $this->accountFromDocument;
    }

    public function getAccountToDocument(): string
    {
        return $this->accountToDocument;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}