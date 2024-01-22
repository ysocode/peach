<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\InputCLI;
use YSOCode\Peach\OutputCLI;
use YSOCode\Peach\Commands\Command;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class InitCommand extends Command implements CommandInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    const SIGNATURE = [
        'command' => 'peach:init',
        '--with' => 'The services that should be included in the initialization',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init YSOCode Peach\'s default Docker Compose file';

    /**
     * Run the console command.
     * 
     * @param InputCLI $input
     * @param OutputCLI $output
     * @return bool
     */
    public function handle(InputCLI $input, OutputCLI $output): bool
    {
        $output->write('Choose the services you want to include in the initialization:')->output();
        $input->readInput();

        return true;
    }
}
