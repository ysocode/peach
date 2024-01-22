<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Commands\Interfaces\CommandInterface;

abstract class Command implements CommandInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    const SIGNATURE = [];

    /**
     * Returns the signature of the command.
     *
     * @param string $parameter The name of the parameter.
     * @return string The signature of the command.
     */
    public static function signature(string $parameter): string
    {
        return isset(static::SIGNATURE[$parameter]) ? static::SIGNATURE[$parameter] : 'Parameter not found';
    }
}
