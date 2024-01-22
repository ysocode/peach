<?php

namespace YSOCode\Peach;

use YSOCode\Peach\Interfaces\InputInterface;

class InputCLI implements InputInterface
{
    /**
     * The script being runing.
     *
     * @var string $script
     */
    protected string $script;

    /**
     * The requested command.
     *
     * @var string $command
     */
    protected string $command;

    /**
     * The arguments from the requested command.
     *
     * @var array<string, array> $arguments
     */
    protected array $arguments = [];

    /**
     * Create a new InputCLI instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->readScriptArguments();
    }

    /**
     * Read the script arguments.
     *
     * @return void
     */
    protected function readScriptArguments()
    {
        $scriptArguments = $this->getScriptArguments();

        $this->script = array_shift($scriptArguments);
        $this->command = array_shift($scriptArguments);

        if ($scriptArguments) {

            $this->setArguments($scriptArguments);
        }
    }

    /**
     * Get the script arguments.
     *
     * @return array<int, string>
     */
    protected function getScriptArguments(): array
    {
        $arguments = $_SERVER['argv'];

        return $arguments;
    }

    /**
     * Read a line from the standard input (STDIN).
     *
     * @return string
     */
    public function readInput(): string
    {
        return trim(fgets(STDIN));
    }

    /**
     * Get the requested command.
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Set arguments from the requested command.
     *
     * @param array<int, string> $scriptArguments
     * @return void
     */
    protected function setArguments(array $scriptArguments): void
    {
        foreach ($scriptArguments as $value) {
            if (substr($value, 0, 2) === '--') {

                $argument = $value;
                $this->arguments[$argument] = [];

                continue;
            }

            if (isset($argument)) {

                $this->arguments[$argument][] = $value;

                continue;
            }

            $this->arguments[$value] = [];
        }
    }

    /**
     * Get arguments from the requested command.
     *
     * @return array<string, array>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
