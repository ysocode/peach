<?php

namespace YSOCode\Peach;

class OutputCLI
{
    protected string $output;

    /**
     * Prepare output.
     *
     * @param string $toWrite
     * @return static
     */
    public function write(string $toWrite)
    {
        $this->output = $toWrite . PHP_EOL;

        return $this;
    }

    /**
     * Write in standard output (STDOUT).
     *
     * @return bool
     */
    public function output(): bool
    {
        return !! fwrite(STDOUT, $this->output) ?: false;
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
}
