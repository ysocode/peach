<?php

namespace YSOCode\Peach\Commands\Interfaces;

use YSOCode\Peach\Basket;

interface CommandInterface
{
    /**
     * Returns the command name.
     *
     * @return string|false
     */
    public function getCommand();

    /**
     * Returns the signature of the command.
     *
     * @param string $parameter
     * @return string|array|false
     */
    public function getSignature(string $parameter = '');

    /**
     * Returns the description of the command.
     *
     * @return string|false
     */
    public function getDescription();

    /**
     * Run command.
     *
     * @param Basket $basket
     * @return bool
     */
    public function handle(Basket $basket): bool;
}
