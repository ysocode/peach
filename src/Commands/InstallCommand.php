<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\InputCLI;
use YSOCode\Peach\OutputCLI;
use YSOCode\Peach\Commands\Command;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class InstallCommand extends Command implements CommandInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    const SIGNATURE = [
        'command' => 'peach:install',
        '--with' => 'The services that should be included in the installation',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install YSOCode Peach\'s default Docker Compose file';

    /**
     * Run the console command.
     * 
     * @param InputCLI $input
     * @param OutputCLI $output
     * @return bool
     */
    public function handle(InputCLI $input, OutputCLI $output): bool
    {
        $output->write('Choose the services you want to include in the installation:')->output();
        $input->readInput();

        return true;
    }
}
