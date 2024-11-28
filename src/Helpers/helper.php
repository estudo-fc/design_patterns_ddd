<?php

if (!function_exists("dd")) {
    function dd($arg, ...$args): void
    {
        var_dump($arg, $args);
        die();
    }
}

if (!function_exists("dump")) {
    function dump($arg, ...$args): void
    {
        var_dump($arg, $args);
    }
}