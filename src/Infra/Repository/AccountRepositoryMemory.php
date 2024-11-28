<?php

namespace Maruko\DddPhp\Infra\Repository;

use Exception;
use Maruko\DddPhp\Domain\Entity\Account;
use Maruko\DddPhp\Domain\Repository\AccountRepository;

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
        foreach ($this->accounts as $account) {
            if ($account->getDocument() === $accountDocument) {
                return $account;
            }
        }

        throw new Exception("Account not found.");
    }

    public function save(Account $account)
    {
        $this->accounts[] = $account;
    }
}