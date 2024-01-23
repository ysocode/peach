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
     * The command name.
     *
     * @var string
     */
    protected string $command = 'peach:install';

    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    protected static array $signature = [
        '--with' => 'The services that should be included in the installation',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install YSOCode Peach\'s default Docker Compose file';

    /**
     * Returns the command name.
     * 
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

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
