<?php

namespace Maruko\DddPhp\Application\Command;

interface Command
{
    public function getOperation(): string;
}