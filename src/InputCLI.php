<?php

namespace YSOCode\Peach;

class InputCLI
{
    /**
     * The script being runing.
     *
     * @var string $script
     */
    protected string $script;

    /**
     * The command.
     *
     * @var string $command
     */
    protected string $command;

    /**
     * The command arguments.
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
     * Read a line from the standard input (STDIN).
     *
     * @return string
     */
    public function readInput(): string
    {
        return trim(fgets(STDIN));
    }

    /**
     * Set the command arguments.
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
     * Get the CLI arguments.
     *
     * @return array<int, string>
     */
    protected function getScriptArguments(): array
    {
        $arguments = $_SERVER['argv'];

        return $arguments;
    }

    /**
     * Get the command.
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Get the arguments.
     *
     * @return array<string, array>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
