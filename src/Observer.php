<?php

namespace Maruko\DddPhp;

interface Observer
{
    public function notify(Command $command): void;
    public function getOperation(): string;
}