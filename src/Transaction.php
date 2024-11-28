<?php

namespace Maruko\DddPhp;


class Transaction
{
    public function __construct(
        readonly string $type,
        readonly int $amount
    )
    {
    }
}