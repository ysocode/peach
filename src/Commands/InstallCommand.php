<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Interfaces\InputInterface;
use YSOCode\Peach\Interfaces\OutputInterface;
use YSOCode\Peach\Commands\Traits\CommandTrait;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class InstallCommand implements CommandInterface
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    protected static array $signature = [
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    public function handle(InputInterface $input, OutputInterface $output): bool
    {
        $output->writeOutput('Choose the services you want to include in the installation:');
        $input->readInput();

        return true;
    }
}
