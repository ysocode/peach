<?php

namespace YSOCode\Peach\Interfaces;

interface OutputInterface
{
    /**
     * Prepare output.
     *
     * @param string $toWrite
     * @return static
     */
    public function write(string $toWrite);

    /**
     * Write in output channel.
     *
     * @return bool
     */
    public function output(): bool;

    /**
     * Write in output channel.
     *
     * @param string $toWrite
     * @return bool
     */
    public function writeOutput(string $toWrite): bool;
}
