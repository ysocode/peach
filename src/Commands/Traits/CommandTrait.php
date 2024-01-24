<?php

namespace YSOCode\Peach\Commands\Traits;

use YSOCode\Peach\Basket;

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

    /**
     * Checks if a docker-compose.yml file already exists in the current directory.
     * 
     * @param Basket $basket
     * @return bool
     */
    protected function dockerComposeExists(Basket $basket): bool
    {
        $composePath = $basket->getBasePath('/docker-compose.yml');

        return file_exists($composePath);
    }
}
