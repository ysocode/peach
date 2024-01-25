<?php

namespace YSOCode\Peach;

use YSOCode\Peach\Interfaces\OutputInterface;

class OutputCLI implements OutputInterface
{
    /**
     * The output.
     *
     * @var string $output
     */
    protected string $output = '';

    /**
     * The output error.
     *
     * @var string $outputError
     */
    protected string $outputError = '';

    /**
     * Prepare output.
     *
     * @param string $toWrite
     * @return OutputCLI
     */
    public function write(string $toWrite): OutputCLI
    {
        $this->output .= $toWrite . PHP_EOL;

        return $this;
    }

    /**
     * Write in standard output (STDOUT).
     *
     * @return bool
     */
    public function output(): bool
    {
        $success = !! fwrite(STDOUT, $this->output) ?: false;
        $this->output = '';
        return $success;
    }

    /**
     * Write in standard output (STDOUT).
     *
     * @param string $toWrite
     * @return bool
     */
    public function writeOutput(string $toWrite): bool
    {
        $this->write($toWrite);
        return $this->output();
    }

    /**
     * Prepare output error.
     *
     * @param string $toWrite
     * @return OutputCLI
     */
    public function writeError(string $toWrite)
    {
        $this->outputError .= $toWrite . PHP_EOL;

        return $this;
    }

    /**
     * Write in output error (STDERR).
     *
     * @return bool
     */
    public function outputError(): bool
    {
        $success = !! fwrite(STDERR, $this->outputError) ?: false;
        $this->outputError = '';
        return $success;
    }

    /**
     * Write in output error (STDERR).
     *
     * @param string $toWrite
     * @return bool
     */
    public function writeOutputError(string $toWrite): bool
    {
        $this->writeError($toWrite);

        return $this->outputError();
    }
}
