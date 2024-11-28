<?php

namespace Maruko\DddPhp\Domain\Service;

use Exception;
use Maruko\DddPhp\Domain\Entity\Account;

class TransferService
{

    /**
     * @throws Exception
     */
    public function transfer(Account $from, Account $to, int $amount)
    {
        if ($amount <= 0) throw new Exception("Amount must be greater than 0");
        if ($amount > $from->getBalance()) throw new Exception("Insufficient balance");

        $from->debit($amount);
        $to->credit($amount);

    }

}