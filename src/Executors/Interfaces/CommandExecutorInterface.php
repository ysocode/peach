<?php

namespace YSOCode\Peach\Executors\Interfaces;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

interface CommandExecutorInterface
{
    /**
     * Create a new CommandExecutor instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket);

    /**
     * Register the command.
     *
     * @param CommandInterface|string $command
     * @return void
     */
    public function registerCommand($command): void;

    /**
     * get the registered commands.
     *
     * @return array<int, CommandInterface>
     */
    public function getRegisteredCommands(): array;

    /**
     * Run the command.
     *
     * @return void
     */
    public function run(): void;

    /**
     * Indicates if has the requested command
     *
     * @return boolean
     */
    public function hasRequestedCommand(): bool;

    /**
     * Mark the commands as attached.
     *
     * @return void
     */
    public function markAsAttached(): void;

    /**
     * Indicates if the commands have been attached.
     *
     * @return bool
     */
    public function isAttached(): bool;
}
