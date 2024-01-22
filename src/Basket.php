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
     * Executors.
     *
     * @var array<CommandExecutorInterface> $executors
     */
    protected array $executors = [];

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
     * The executors who have the requested command.
     *
     * @var array<CommandExecutorInterface> $hasRequestedCommand
     */
    protected $hasRequestedCommand = [];

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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Register the executor.
     *
     * @param CommandExecutorInterface|string $executor
     * @return void
     */
    public function registerExecutor($executor): void
    {
        if (is_string($executor)) {

            if (! class_exists($executor)) {

                throw new Exception("Class {$executor} does not exist.");
            }

            $executor = new $executor($this);
        }

        if (! ($executor instanceof CommandExecutorInterface)) {

            throw new Exception('CommandExecutor ' . get_class($executor) . ' must implement ' . CommandExecutorInterface::class . '.');
        }

        $this->executors[] = $executor;
        $executor->markAsAttached();
    }

    /**
     * Run all executors.
     *
     * @return void
     */
    protected function runExecutors(): void
    {
        foreach ($this->executors as $executor) {

            $executor->run();
            if ($executor->hasRequestedCommand()) {
                $this->hasRequestedCommand[] = $executor;
            }
        }
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

        $this->runExecutors();

        $this->callBootedCallbacks();

        if (! $this->hasRequestedCommand) {

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
