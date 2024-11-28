<?php

namespace Maruko\DddPhp;

use Exception;

class AccountRepositoryMemory implements AccountRepository
{


    /**
     * @var array<Account>
     */
    private array $accounts = [];

    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function get(string $accountDocument): Account
    {
        return array_reduce(
            $this->accounts,
            fn(?Account $carry, Account $account) => $account->getDocument() === $accountDocument
                ? $account
                : throw new Exception("Account not found."),
        );
    }

    public function save(Account $account)
    {
        $this->accounts[] = $account;
    }
}