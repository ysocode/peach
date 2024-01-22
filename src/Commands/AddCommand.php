<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Interfaces\InputInterface;
use YSOCode\Peach\Interfaces\OutputInterface;
use YSOCode\Peach\Commands\Traits\CommandTrait;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class AddCommand implements CommandInterface
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    protected static array $signature = [
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    public function handle(InputInterface $input, OutputInterface $output): bool
    {
        return true;
    }
}
