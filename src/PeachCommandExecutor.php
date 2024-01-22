<?php

namespace YSOCode\Peach;

use Exception;
use YSOCode\Peach\OutputCLI;
use YSOCode\Peach\Commands\AddCommand;
use YSOCode\Peach\Commands\InitCommand;
use YSOCode\Peach\Traits\CommandExecutorTrait;
use YSOCode\Peach\Interfaces\CommandExecutorInterface;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class PeachCommandExecutor implements CommandExecutorInterface
{
    use CommandExecutorTrait;

    /**
     * The commands.
     *
     * @var array<CommandInterface> $commands
     */
    protected array $commands = [];

    /**
     * Informer if has the requested command
     *
     * @var bool
     */
    protected bool $hasRequestedCommand = false;

    /**
     * Create a new PeachCommandCollection instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;

        $this->registerBaseCommands();
    }

    /**
     * Register the console command.
     *
     * @param CommandInterface|string $command
     * @return void
     */
    public function registerCommand($command): void
    {
        if ($this->attached) {

            throw new Exception('Cannot register commands after they have been attached.');
        }

        if (is_string($command)) {

            if (!class_exists($command)) {

                throw new Exception("Class {$command} does not exist.");
            }

            $command = new $command();
        }

        if (!($command instanceof CommandInterface)) {

            throw new Exception('Command ' . get_class($command) . ' must implement ' . CommandInterface::class . '.');
        }

        $this->commands[$command->signature('command')] = $command;
    }

    /**
     * get the registered command.
     *
     * @param string $command
     * @return CommandInterface|false
     */
    public function getRegisteredCommand(string $command)
    {
        if (isset($this->commands[$command])) {

            return $this->commands[$command];
        }

        return false;
    }

    /**
     * get the registered commands.
     *
     * @return array<CommandInterface>
     */
    public function getRegisteredCommands(): array
    {
        return $this->commands;
    }

    /**
     * Register all of the base console commands.
     *
     * @return void
     */
    protected function registerBaseCommands(): void
    {
        $this->registerCommand(InitCommand::class);
        $this->registerCommand(AddCommand::class);
    }

    /**
     * Run the console command.
     *
     * @return void
     */
    public function run(): void
    {
        if ($command = $this->getRegisteredCommand($this->basket->getInput()->getCommand())) {

            $command->handle($this->basket->getInput(), $this->basket->getOutput());

            $this->hasRequestedCommand = true;
        }
    }

    public function hasRequestedCommand(): bool
    {
        return $this->hasRequestedCommand;
    }
}
