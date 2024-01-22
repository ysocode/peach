<?php

namespace YSOCode\Peach\Commands\Traits;

trait CommandTrait
{
    /**
     * Returns the signature of the command.
     *
     * @param string $parameter The name of the parameter.
     * @return string|false
     */
    public static function signature(string $parameter)
    {
        return isset(static::$signature[$parameter]) ? static::$signature[$parameter] : false;
    }
}
