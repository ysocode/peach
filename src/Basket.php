<?php

namespace YSOCode\Peach;

use Exception;
use YSOCode\Peach\InputCLI;
use YSOCode\Peach\OutputCLI;
use YSOCode\Peach\Interfaces\CommandExecutorInterface;

class Basket
{
    /**
     * Executors.
     *
     * @var array<CommandExecutorInterface> $executors
     */
    protected array $executors = [];

    /**
     * The Input.
     *
     * @var InputCLI $input
     */
    protected InputCLI $input;

    /**
     * The Output.
     *
     * @var OutputCLI $output
     */
    protected OutputCLI $output;

    /**
     * The array of booting callbacks.
     *
     * @var callable[] $bootingCallbacks
     */
    protected $bootingCallbacks = [];

    /**
     * The array of booted callbacks.
     *
     * @var callable[] $bootedCallbacks
     */
    protected $bootedCallbacks = [];

    /**
     * The array of executors have the requested command.
     *
     * @var array<CommandExecutorInterface> $hasRequestedCommand
     */
    protected $hasRequestedCommand = [];

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * Create a new Basket instance.
     *
     * @param InputCLI $input
     * @param OutputCLI $output
     * @return void
     */
    public function __construct(InputCLI $input, OutputCLI $output)
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
    }

    /**
     * Run all executors.
     *
     * @return array<OutputCLI>
     */
    protected function runExecutors(): array
    {
        $outputs = [];
        foreach ($this->executors as $executor) {

            $executor->run();
            if ($executor->hasRequestedCommand()) {
                $this->hasRequestedCommand[] = $executor;
            }
        }

        return $outputs;
    }

    /**
     * Determine if the Basket has booted.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Register a new boot listener.
     *
     * @param callable $callback
     * @return void
     */
    public function registerBooting($callback): void
    {
        $this->bootingCallbacks[] = $callback;
    }

    /**
     * Register a new "booted" listener.
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

            $this->output->writeOutput('The requested command not exists.');
        }
    }

    /**
     * Get the Input.
     *
     * @return InputCLI
     */
    public function getInput(): InputCLI
    {
        return $this->input;
    }

    /**
     * Get the Output.
     *
     * @return OutputCLI
     */
    public function getOutput(): OutputCLI
    {
        return $this->output;
    }
}
