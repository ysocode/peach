<?php

namespace YSOCode\Peach\Commands\Interfaces;

use YSOCode\Peach\Interfaces\InputInterface;
use YSOCode\Peach\Interfaces\OutputInterface;

interface CommandInterface
{
    /**
     * Run the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    public function handle(InputInterface $input, OutputInterface $output): bool;

    /**
     * Returns the signature of the command.
     *
     * @param string $parameter The name of the parameter.
     * @return string|false
     */
    public static function signature(string $parameter);
}