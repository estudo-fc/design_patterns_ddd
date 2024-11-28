<?php

namespace Maruko\DddPhp\Infra\Queue;

use Maruko\DddPhp\Application\Command\Command;

interface Observer
{
    public function notify(Command $command): void;
    public function getOperation(): string;
}