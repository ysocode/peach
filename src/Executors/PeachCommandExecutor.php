<?php

namespace YSOCode\Peach\Executors;

use Exception;
use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\AddCommand;
use YSOCode\Peach\Commands\InstallCommand;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;
use YSOCode\Peach\Executors\Traits\CommandExecutorTrait;
use YSOCode\Peach\Executors\Interfaces\CommandExecutorInterface;

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
     * Indicates if has the requested command
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
        if ($this->isAttached()) {

            throw new Exception('Cannot register commands after they have been attached.');
        }

        if (is_string($command)) {

            if (!class_exists($command)) {

                throw new Exception("Class {$command} does not exist.");
            }

            $command = new $command();
        }

        if (! ($command instanceof CommandInterface)) {

            throw new Exception('Command ' . get_class($command) . ' must implement ' . CommandInterface::class . '.');
        }

        if (! $command->getCommand()) {

            throw new Exception('Command ' . get_class($command) . ' must have a command name.');
        }
        
        $this->commands[$command->getCommand()] = $command;
    }

    /**
     * get the registered console command.
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
     * get the registered console commands.
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
        $this->registerCommand(InstallCommand::class);
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

            $command->handle($this->basket);

            $this->hasRequestedCommand = true;
        }
    }

    /**
     * Indicates if has the requested command
     *
     * @return boolean
     */
    public function hasRequestedCommand(): bool
    {
        return $this->hasRequestedCommand;
    }
}
