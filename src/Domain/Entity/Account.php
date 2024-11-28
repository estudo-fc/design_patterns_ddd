<?php

namespace Maruko\DddPhp\Domain\Entity;

use Exception;
use Maruko\DddPhp\Domain\Builder\AccountBuilder;

class Account
{
    private ?string $bank;
    private ?string $branch;
    private ?string $account;
    private ?string $document;


    /**
     * @var array<Transaction>
     */
    private array $transaction;

    public function __construct(
        AccountBuilder $accountBuilder
    )
    {
        $this->bank = $accountBuilder->getBank();
        $this->branch = $accountBuilder->getBranch();
        $this->account = $accountBuilder->getAccount();
        $this->document = $accountBuilder->getDocument();

        $this->transaction = [];
    }

    /**
     * @throws Exception
     */
    public function credit(int $amount): void
    {

        if ($amount <= 0) throw new Exception("Amount must be greater than 0");

        $this->transaction[] = new Transaction("credit", $amount);
    }

    /**
     * @throws Exception
     */
    public function debit(int $amount): void
    {

        if ($amount <= 0) throw new Exception("Amount must be greater than 0");
        if ($amount > $this->getBalance()) throw new Exception("Insufficient balance");

        $this->transaction[] = new Transaction("debit", $amount);
    }

    public function getBalance(): int
    {
        $balance = 0;
        foreach ($this->transaction as $transaction) {
            if ($transaction->type === "credit") $balance += $transaction->amount;
            if ($transaction->type === "debit") $balance -= $transaction->amount;
        }
        return $balance;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }


}