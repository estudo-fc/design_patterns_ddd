<?php

namespace Maruko\DddPhp;

interface AccountRepository
{
    public function get(string $accountDocument): Account;
    public function save(Account $account);
}