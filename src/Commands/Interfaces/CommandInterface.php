<?php

namespace YSOCode\Peach\Commands\Interfaces;

use YSOCode\Peach\Interfaces\InputInterface;
use YSOCode\Peach\Interfaces\OutputInterface;

interface CommandInterface
{
    /**
     * Returns the command name.
     *
     * @return string
     */
    public function getCommand(): string;

    /**
     * Returns the signature of the command.
     *
     * @param string $parameter The name of the parameter.
     * @return string|array|false
     */
    public static function getSignature(string $parameter = '');

    /**
     * Run the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    public function handle(InputInterface $input, OutputInterface $output): bool;
}
