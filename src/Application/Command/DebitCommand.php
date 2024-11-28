<?php

namespace Maruko\DddPhp\Application\Command;

class DebitCommand implements Command
{
    private string $operation = 'debit';

    public function __construct(
        private readonly string $accountDocument,
        private readonly int    $amount
    )
    {
    }

    public function getAccountDocument(): string
    {
        return $this->accountDocument;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}