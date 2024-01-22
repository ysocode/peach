<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\InputCLI;
use YSOCode\Peach\OutputCLI;
use YSOCode\Peach\Commands\Command;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class AddCommand extends Command implements CommandInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    const SIGNATURE = [
        'command' => 'peach:add',
        '--services' => 'The services that should be added',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a service to an existing Peach installation';

    /**
     * Run the console command.
     * 
     * @param InputCLI $input
     * @param OutputCLI $output
     * @return bool
     */
    public function handle(InputCLI $input, OutputCLI $output): bool
    {
        return true;
    }
}
