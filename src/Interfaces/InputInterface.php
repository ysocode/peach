<?php

namespace YSOCode\Peach\Interfaces;

interface InputInterface
{
    /**
     * Read a line from the input channel.
     *
     * @return string
     */
    public function readInput(): string;

    /**
     * Get the requested command.
     *
     * @return string
     */
    public function getCommand(): string;

    /**
     * Get arguments from the requested command.
     *
     * @return array<string, array>
     */
    public function getArguments(): array;
}