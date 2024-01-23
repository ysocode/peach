<?php

namespace YSOCode\Peach\Commands\Traits;

trait CommandTrait
{
    /**
     * Returns the signature of the command.
     *
     * @param string $parameter The name of the parameter.
     * @return string|array|false
     */
    public static function getSignature(string $parameter = '')
    {
        if (isset(static::$signature)) {

            return isset(static::$signature[$parameter]) ? static::$signature[$parameter] : static::$signature;
        }

        return false;
    }
}
