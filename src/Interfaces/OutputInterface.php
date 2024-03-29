<?php

namespace YSOCode\Peach\Interfaces;

interface OutputInterface
{
    /**
     * Prepare output.
     *
     * @param string $toWrite
     * @return OutputInterface
     */
    public function write(string $toWrite): OutputInterface;

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

    /**
     * Prepare output error.
     *
     * @param string $toWrite
     * @return OutputInterface
     */
    public function writeError(string $toWrite);

    /**
     * Write in output error channel.
     *
     * @return bool
     */
    public function outputError(): bool;

    /**
     * Write in output error channel.
     *
     * @param string $toWrite
     * @return bool
     */
    public function writeOutputError(string $toWrite): bool;
}
