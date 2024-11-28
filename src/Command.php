<?php

namespace Maruko\DddPhp;

interface Command
{
    public function getOperation(): string;
}