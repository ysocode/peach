<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\Traits\CommandTrait;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;

class AddCommand implements CommandInterface
{
    use CommandTrait;

    /**
     * The command name.
     *
     * @var string
     */
    protected string $command = 'peach:add';

    /**
     * The name and signature of the console command.
     *
     * @var array<string, string>
     */
    protected static array $signature = [
        '--services' => 'The services that should be added',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a service to an existing Peach installation';

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
     * @param Basket $basket
     * @return bool
     */
    public function handle(Basket $basket): bool
    {
        return true;
    }
}
