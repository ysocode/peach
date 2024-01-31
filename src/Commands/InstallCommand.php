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
     * @var string|null
     */
    protected $command = 'peach:install';

    /**
     * The signature of the console command.
     *
     * @var null|array<string, string>
     */
    protected $signature = [
        '--with' => 'The services that should be included in the installation',
    ];

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Install YSOCode Peach\'s default Docker Compose file';

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

        $phpServer = $this->doYouWantToInstallWhichPHPServer($basket, $dockerComposeInteraction);

        $dockerComposeInteraction->setPHPServer($phpServer);

        $basket->getOutput()->write(PHP_EOL . 'Services: [' . implode(', ', $dockerComposeInteraction->getAvailableServices()) . ']');
        $basket->getOutput()->write('Choose the services you want to include in the installation:');
        $basket->getOutput()->output();
        
        $chosenServices = $basket->getInput()->readInput();

        if (! $chosenServices) {

            $yesOrNot = $this->doYouWantToInstallTheDefaultServices($basket, $dockerComposeInteraction);

            $actions = [
                'y' => function () use ($basket, $dockerComposeInteraction) {

                    $basket->getOutput()->writeOutput(PHP_EOL . 'Installing default services...');

                    $services = $dockerComposeInteraction->getDefaultServices();
                    $dockerComposeInteraction->buildDockerCompose($services);

                    $basket->getOutput()->writeOutput("Run './vendor/bin/peach up -d' to start the services.");

                    return true;
                },
                'n' => function () use ($basket) {

                    $basket->getOutput()->writeOutput(PHP_EOL . 'No services selected. Exiting...');

                    return false;
                }
            ];

            $actionToExecute = $actions[$yesOrNot];

            return $actionToExecute();
        }

        $basket->getOutput()->writeOutput(PHP_EOL . 'Installing chosen services...');
        
        $chosenServicesAsArray = explode(' ', $chosenServices);
        $dockerComposeInteraction->buildDockerCompose($chosenServicesAsArray);

        $basket->getOutput()->writeOutput("Run './vendor/bin/peach up -d' to start the services.");

        return true;
    }

    /**
     * Ask which server the user wants to install.
     * 
     * @param Basket $basket
     * @param DockerComposeInteraction $dockerComposeInteraction
     * @return string
     */
    protected function doYouWantToInstallWhichPHPServer(Basket $basket, DockerComposeInteraction $dockerComposeInteraction): string
    {
        $basket->getOutput()->write(PHP_EOL . 'PHP servers: [' . implode(', ', $dockerComposeInteraction->getAvailablePHPServers()) . ']');
        $basket->getOutput()->write('Do you want to install which PHP server:');
        $basket->getOutput()->output();
        $phpServer = $basket->getInput()->readInput();

        if (! in_array($phpServer, $dockerComposeInteraction->getAvailablePHPServers())) {

            return $this->doYouWantToInstallWhichPHPServer($basket, $dockerComposeInteraction);
        }

        return $phpServer;
    }

    /**
     * Ask if the user wants to install the default services.
     *
     * @param Basket $basket
     * @param DockerComposeInteraction $dockerComposeInteraction
     * @return string
     */
    protected function doYouWantToInstallTheDefaultServices(Basket $basket, DockerComposeInteraction $dockerComposeInteraction): string
    {
        $basket->getOutput()->write('Default services: [' . implode(', ', $dockerComposeInteraction->getDefaultServices()) . ']');
        $basket->getOutput()->write('Do you want to install the default services: (y/n)');
        $basket->getOutput()->output();
        $yesOrNot = $basket->getInput()->readInput();

        if ($yesOrNot != 'y' && $yesOrNot != 'n') {

            return $this->doYouWantToInstallTheDefaultServices($basket, $dockerComposeInteraction);
        }

        return $yesOrNot;
    }
}
