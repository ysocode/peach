<?php

namespace YSOCode\Peach\Utils;

use Exception;

class ShellCommandExecutor
{
    /**
     * The output of the command.
     *
     * @var array
     */
    protected array $output;

    /**
     * The status code returned by the command.
     *
     * @var int
     */
    protected int $returnedStatusCode;

    const SUCCESS_STATUS_CODE = 0;

    /**
     * Execute a shell command.
     *
     * @param string $command
     * @return ShellCommandExecutor
     */
    public static function executeCommand(string $command)
    {
        exec($command, $output, $returnedStatusCode);

        $instance = new self;
        $instance->returnedStatusCode = $returnedStatusCode;

        if ($returnedStatusCode === self::SUCCESS_STATUS_CODE) {

            $instance->output = $output;
        }

        return $instance;
    }

    /**
     * Get the output of the command.
     *
     * @return array<int, string>
     */
    public function getOutput(): array
    {
        if (isset($this->output)) {

            return $this->output;
        }

        throw new Exception('It is not possible to obtain an output without executing a command.');
    }

    /**
     * Get the status code returned by the command.
     *
     * @return int
     */
    public function getReturnedStatusCode(): int
    {
        if (isset($this->returnedStatusCode)) {

            return $this->returnedStatusCode;
        }

        throw new Exception('It is not possible to obtain an status without executing a command.');
    }
}
