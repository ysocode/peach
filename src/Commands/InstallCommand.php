<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\Traits\CommandTrait;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;
use YSOCode\Peach\Interactions\DockerComposeInteraction;

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
     * @param Basket $basket
     * @return bool
     */
    public function handle(Basket $basket): bool
    {
        if ($this->dockerComposeExists($basket)) {

            $basket->getOutput()->writeError('A docker-compose.yml file already exists in this directory.');
            $basket->getOutput()->writeError("Run './vendor/bin/basket peach:add' to add services.");
            $basket->getOutput()->outputError();

            return false;
        }

        $dockerComposeInteraction = new DockerComposeInteraction($basket);

        $basket->getOutput()->write('Services: [' . implode(', ', $dockerComposeInteraction->getAvailableServices()) . ']');
        $basket->getOutput()->write('Choose the services you want to include in the installation:');
        $basket->getOutput()->output();
        
        $chosenServices = $basket->getInput()->readInput();

        if (! $chosenServices) {
            
            function doYouWantToInstallTheDefaultServices(Basket $basket, DockerComposeInteraction $dockerComposeInteraction)
            {
                $basket->getOutput()->write('Default services: [' . implode(', ', $dockerComposeInteraction->getDefaultServices()) . ']');
                $basket->getOutput()->write('Do you want to install the default services: (y/n)');
                $basket->getOutput()->output();
                $yesOrNot = $basket->getInput()->readInput();

                if ($yesOrNot != 'y' && $yesOrNot != 'n') {
    
                    return doYouWantToInstallTheDefaultServices($basket, $dockerComposeInteraction);
                }

                return $yesOrNot;
            }

            $yesOrNot = doYouWantToInstallTheDefaultServices($basket, $dockerComposeInteraction);

            $actions = [
                'y' => function () use ($basket, $dockerComposeInteraction) {
                    $basket->getOutput()->writeOutput(PHP_EOL . 'Installing default services...');
                    $services = $dockerComposeInteraction->getDefaultServices();
                    $dockerComposeInteraction->buildDockerCompose($services);
                    return true;
                },
                'n' => function () use ($basket) {
                    $basket->getOutput()->writeOutput(PHP_EOL . 'No services selected. Exiting...');
                    return false;
                }
            ];

            if ($actions[$yesOrNot]()) {

                $basket->getOutput()->writeOutput("Run './vendor/bin/peach up -d' to start the services.");

                return true;
            }

            return false;
        }

        $basket->getOutput()->writeOutput(PHP_EOL . 'Installing chosen services...');
        $chosenServicesAsArray = explode(' ', $chosenServices);
        $dockerComposeInteraction->buildDockerCompose($chosenServicesAsArray);

        $basket->getOutput()->writeOutput("Run './vendor/bin/peach up -d' to start the services.");

        return true;
    }
}
