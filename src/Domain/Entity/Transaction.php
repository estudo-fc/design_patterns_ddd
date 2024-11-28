<?php

namespace Maruko\DddPhp\Domain\Entity;


class Transaction
{
    public function __construct(
        readonly string $type,
        readonly int $amount
    )
    {
    }
}