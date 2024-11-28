<?php

namespace Maruko\DddPhp;

use Exception;

class TransferService
{

    /**
     * @throws Exception
     */
    public function transfer(Account $from, Account $to, int $amount)
    {
        if ($amount <= 0) throw new Exception("Amount must be greater than 0");
        if ($amount > $from->getBalance()) throw new Exception("Insufficient balance");

        $debitCommandFrom = new DebitCommand($from, $amount);
        $debitCommandFrom->execute();

        $creditCommandTo = new CreditCommand($to, $amount);
        $creditCommandTo->execute();

    }

}