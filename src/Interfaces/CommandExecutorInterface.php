<?php

namespace YSOCode\Peach\Interfaces;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;
use YSOCode\Peach\InputCLI;
use YSOCode\Peach\OutputCLI;

interface CommandExecutorInterface
{
    /**
     * Create a new CommandCollection instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket);

    /**
     * Register the console command.
     *
     * @param CommandInterface|string $command
     * @return void
     */
    public function registerCommand($command): void;

    /**
     * get the registered commands.
     *
     * @return array<CommandInterface>
     */
    public function getRegisteredCommands(): array;

    /**
     * Run the console command.
     *
     * @return void
     */
    public function run(): void;

    /**
     * Check if has the requested command
     *
     * @return boolean
     */
    public function hasRequestedCommand(): bool;
}