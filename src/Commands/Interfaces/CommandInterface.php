<?php

namespace YSOCode\Peach\Commands\Interfaces;

use YSOCode\Peach\InputCLI;
use YSOCode\Peach\OutputCLI;

interface CommandInterface
{
    /**
     * Run the console command.
     *
     * @param InputCLI $input
     * @param OutputCLI $output
     * @return bool
     */
    public function handle(InputCLI $input, OutputCLI $output): bool;

    /**
     * Returns the signature of the command.
     *
     * @param string $parameter The name of the parameter.
     * @return string|false
     */
    public static function signature(string $parameter);
}