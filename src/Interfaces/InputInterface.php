<?php

namespace YSOCode\Peach\Interfaces;

interface InputInterface
{
    /**
     * Read a input channel.
     *
     * @return string
     */
    public function readInput(): string;

    /**
     * Get the command.
     *
     * @return string
     */
    public function getCommand(): string;

    /**
     * Get the arguments.
     *
     * @return array<string, array>
     */
    public function getArguments(): array;
}