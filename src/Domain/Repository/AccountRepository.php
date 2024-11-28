<?php

namespace Maruko\DddPhp\Domain\Repository;

use Maruko\DddPhp\Domain\Entity\Account;

interface AccountRepository
{
    public function get(string $accountDocument): Account;
    public function save(Account $account);
}