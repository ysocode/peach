<?php

namespace YSOCode\Peach;

use Error;
use Exception;
use YSOCode\Peach\Interfaces\InputInterface;
use YSOCode\Peach\Interfaces\OutputInterface;
use YSOCode\Peach\ErrorHandler\Interfaces\ErrorHandlerInterface;
use YSOCode\Peach\Executors\Interfaces\CommandExecutorInterface;

class Basket
{
    /**
     * Base path.
     *
     * @var string $basePath
     */
    protected string $basePath;

    /**
     * Command executors.
     *
     * @var array<CommandExecutorInterface> $commandExecutors
     */
    protected array $commandExecutors = [];

    /**
     * The input channel.
     *
     * @var InputInterface $input
     */
    protected InputInterface $input;

    /**
     * The output channel.
     *
     * @var OutputInterface $output
     */
    protected OutputInterface $output;

    /**
     * The booting callbacks.
     *
     * @var callable[] $bootingCallbacks
     */
    protected $bootingCallbacks = [];

    /**
     * The booted callbacks.
     *
     * @var callable[] $bootedCallbacks
     */
    protected $bootedCallbacks = [];

    /**
     * The command executors who have the requested command.
     *
     * @var array<CommandExecutorInterface> $commandExecutorsWithRequestedCommand
     */
    protected $commandExecutorsWithRequestedCommand = [];

    /**
     * The command not found error handler.
     *
     * @var ErrorHandlerInterface $commandNotFoundErrorHandler
     */
    protected ErrorHandlerInterface $commandNotFoundErrorHandler;

    /**
     * Indicates if the Basket has booted.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * Create a new Basket instance.
     *
     * @param string $basePath
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function __construct(string $basePath, InputInterface $input, OutputInterface $output)
    {
        $this->basePath = $basePath;

        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Get the base path.
     *
     * @param string $path
     * @return string
     */
    public function getBasePath(string $path = ''): string
    {
        return $this->basePath . $path;
    }

    /**
     * Register the command executor.
     *
     * @param CommandExecutorInterface|string $commandExecutor
     * @return void
     */
    public function registerCommandExecutor($commandExecutor): void
    {
        if (is_string($commandExecutor)) {

            if (! class_exists($commandExecutor)) {

                throw new Exception("Class {$commandExecutor} does not exist.");
            }

            $commandExecutor = new $commandExecutor($this);
        }

        if (! ($commandExecutor instanceof CommandExecutorInterface)) {

            throw new Exception('CommandExecutor ' . get_class($commandExecutor) . ' must implement ' . CommandExecutorInterface::class . '.');
        }

        $this->commandExecutors[] = $commandExecutor;
        $commandExecutor->markAsAttached();
    }

    /**
     * Get the registered command executors.
     *
     * @return array<CommandExecutorInterface>
     */
    public function getRegisteredCommandExecutors(): array
    {
        return $this->commandExecutors;
    }

    /**
     * Run all command executors.
     *
     * @return void
     */
    protected function runCommandExecutors(): void
    {
        foreach ($this->getRegisteredCommandExecutors() as $commandExecutor) {

            $commandExecutor->run();
            if ($commandExecutor->hasRequestedCommand()) {
                $this->registerCommandExecutorsWithRequestedCommand($commandExecutor);
            }
        }
    }

    /**
     * Register the command executors who have the requested command.
     *
     * @param CommandExecutorInterface $commandExecutor
     * @return void
     */
    protected function registerCommandExecutorsWithRequestedCommand(CommandExecutorInterface $commandExecutor): void
    {
        $this->commandExecutorsWithRequestedCommand[] = $commandExecutor;
    }

    /**
     * Get the registered command executors who have the requested command.
     *
     * @return array<CommandExecutorInterface>
     */
    protected function getRegisteredCommandExecutorsWithRequestedCommand(): array
    {
        return $this->commandExecutorsWithRequestedCommand;
    }

    /**
     * Indicates if the Basket has booted.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Register a new "booting" callback.
     *
     * @param callable $callback
     * @return void
     */
    public function registerBooting($callback): void
    {
        $this->bootingCallbacks[] = $callback;
    }

    /**
     * Register a new "booted" callback.
     *
     * @param callable $callback
     * @return void
     */
    public function registerBooted($callback)
    {
        $this->bootedCallbacks[] = $callback;

        if ($this->isBooted()) {
            $callback($this);
        }
    }

    /**
     * Call the registered booting callbacks.
     *
     * @return void
     */
    public function callBootingCallbacks(): void
    {
        foreach ($this->bootingCallbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * Call the registered booted callbacks.
     *
     * @return void
     */
    public function callBootedCallbacks(): void
    {
        foreach ($this->bootedCallbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * Boot the Basket.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->booted = true;
        
        $this->callBootingCallbacks();

        $this->runCommandExecutors();

        $this->callBootedCallbacks();

        if (! $this->getRegisteredCommandExecutorsWithRequestedCommand()) {

            $this->getCommandNotFoundErrorHandler()->handle();
        }
    }

    /**
     * Get the input channel.
     *
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * Get the output channel.
     *
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    /**
     * Set the command not found error handler.
     *
     * @param ErrorHandlerInterface|string $commandNotFoundErrorHandler
     * @return void
     */    
    public function setCommandNotFoundErrorHandler($commandNotFoundErrorHandler): void
    {
        if (is_string($commandNotFoundErrorHandler)) {

            if (! class_exists($commandNotFoundErrorHandler)) {

                throw new Exception("Class {$commandNotFoundErrorHandler} does not exist.");
            }

            $commandNotFoundErrorHandler = new $commandNotFoundErrorHandler($this);

        }

        if (! ($commandNotFoundErrorHandler instanceof ErrorHandlerInterface)) {

            throw new Exception('ErrorHandler ' . get_class($commandNotFoundErrorHandler) . ' must implement ' . ErrorHandlerInterface::class . '.');
        }

        $this->commandNotFoundErrorHandler = $commandNotFoundErrorHandler;
    }

    /**
     * Get the command not found error handler.
     *
     * @return ErrorHandlerInterface
     */
    public function getCommandNotFoundErrorHandler(): ErrorHandlerInterface
    {
        return $this->commandNotFoundErrorHandler;
    }
}
