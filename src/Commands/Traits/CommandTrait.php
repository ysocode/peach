<?php

namespace YSOCode\Peach\Commands\Traits;

use YSOCode\Peach\Basket;

trait CommandTrait
{
    /**
     * Returns the command name.
     * 
     * @return string|false
     */
    public function getCommand()
    {
        return isset($this->command) ? $this->command : false;
    }

    /**
     * Returns the signature of the command.
     *
     * @param string $parameter
     * @return string|array|false
     */
    public function getSignature(string $parameter = '')
    {
        if (isset($this->signature)) {

            return isset($this->signature[$parameter]) ? $this->signature[$parameter] : $this->signature;
        }

        return false;
    }

    /**
     * Returns the description of the command.
     *
     * @return string|false
     */
    public function getDescription()
    {
        return isset($this->description) ? $this->description : false;
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
