<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\Traits\CommandTrait;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;
use YSOCode\Peach\Interactions\DockerComposeInteraction;

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
        if (! $this->dockerComposeExists($basket)) {

            $basket->getOutput()->writeError('A docker-compose.yml file not already exists in this directory.');
            $basket->getOutput()->writeError("Run './vendor/bin/basket peach:install' to install services.");
            $basket->getOutput()->outputError();

            return false;
        }

        $dockerComposeInteraction = new DockerComposeInteraction($basket);

        $basket->getOutput()->write('Services: [' . implode(', ', $dockerComposeInteraction->getAvailableServices()) . ']');
        $basket->getOutput()->write('Choose the services you want to include in the installation:');
        $basket->getOutput()->output();
        
        $chosenServices = $basket->getInput()->readInput();

        if (! $chosenServices) {
            
            $basket->getOutput()->writeOutput(PHP_EOL . 'No services selected. Exiting...');
            return false;
        }

        $basket->getOutput()->writeOutput(PHP_EOL . 'Installing chosen services...');
        $chosenServicesAsArray = explode(' ', $chosenServices);
        $dockerComposeInteraction->buildDockerCompose($chosenServicesAsArray);

        $basket->getOutput()->writeOutput("Run './vendor/bin/peach up -d' to start the services.");

        return true;
    }
}
