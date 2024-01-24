<?php

namespace YSOCode\Peach\Commands;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Utils\ShellCommandExecutor;
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
     * Selected services.
     *
     * @var array<string>
     */
    protected array $services = [];

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
        $YMLInteraction = new DockerComposeInteraction($basket);

        $basket->getOutput()->write('Services: [' . implode(', ', $YMLInteraction->getAvailableServices()) . ']');
        $basket->getOutput()->write('Choose the services you want to include in the installation:');
        $basket->getOutput()->output();
        
        $chosenServices = $basket->getInput()->readInput();

        if (! $chosenServices) {
            
            $doYouWantToInstallTheDefaultServices = function () use ($basket, $YMLInteraction, &$doYouWantToInstallTheDefaultServices)
            {
                $basket->getOutput()->write('Default services: [' . implode(', ', $YMLInteraction->getDefaultServices()) . ']');
                $basket->getOutput()->write('Do you want to install the default services: (y/n)');
                $basket->getOutput()->output();
                $yesOrNot = $basket->getInput()->readInput();

                if ($yesOrNot != 'y' && $yesOrNot != 'n') {
    
                    return $doYouWantToInstallTheDefaultServices();
                }

                return $yesOrNot;
            };

            $yesOrNot = $doYouWantToInstallTheDefaultServices();

            $actions = [
                'y' => function () use ($basket, $YMLInteraction) {
                    $basket->getOutput()->writeOutput(PHP_EOL . 'Installing default services...');
                    $services = $YMLInteraction->getDefaultServices();
                    $this->services = $services;
                    $YMLInteraction->buildDockerCompose($this->services);
                    return true;
                },
                'n' => function () use ($basket) {
                    $basket->getOutput()->writeOutput(PHP_EOL . 'No services selected. Exiting...');
                    return false;
                }
            ];

            if ($actions[$yesOrNot]()) {

                $basket->getOutput()->writeOutput("Run 'peach up -d' to start the services.");

                return true;
            }

            return false;
        }

        $basket->getOutput()->writeOutput(PHP_EOL . 'Installing chosen services...');
        $this->services = explode(' ', $chosenServices);
        $YMLInteraction->buildDockerCompose($this->services);

        $basket->getOutput()->writeOutput("Run 'peach up -d' to start the services.");

        return true;
    }

    /**
     * Prepare the installation by pulling and building any necessary images.
     * 
     * @param Basket $basket
     * @return void
     */
    protected function prepareInstallation(Basket $basket): void
    {
        // Ensure docker is installed...
        if (!$this->runCommands(['docker info > /dev/null 2>&1'])) {
            return;
        }

        if (count($this->services) > 0) {

            if ($this->runCommands(['./vendor/bin/peach pull ' . implode(' ', $this->services)])) {

                $basket->getOutput()->writeOutput('Peach images installed successfully.');
            }
        }

        if ($this->runCommands(['./vendor/bin/peach build'])) {

            $basket->getOutput()->writeOutput('Peach build successful.');
        }
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return bool
     */
    protected function runCommands($commands): bool
    {
        $returnedStatusCode = ShellCommandExecutor::executeCommand(implode(' && ', $commands))->getReturnedStatusCode();
        return $returnedStatusCode === ShellCommandExecutor::SUCCESS_STATUS_CODE ? true : false;
    }
}
