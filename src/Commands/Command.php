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
     * @return string|false
     */
    public static function signature(string $parameter)
    {
        return isset(static::SIGNATURE[$parameter]) ? static::SIGNATURE[$parameter] : false;
    }
}
