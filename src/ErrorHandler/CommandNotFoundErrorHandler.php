<?php

namespace YSOCode\Peach\ErrorHandler;

use YSOCode\Peach\Basket;
use YSOCode\Peach\Commands\Interfaces\CommandInterface;
use YSOCode\Peach\ErrorHandler\Interfaces\ErrorHandlerInterface;

class CommandNotFoundErrorHandler implements ErrorHandlerInterface
{
    /**
     * The basket instance.
     *
     * @var Basket $basket
     */
    protected Basket $basket;

    /**
     * Create a new ErrorHandler instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function handle(): void
    {
        $registeredCommandExecutors = $this->basket->getRegisteredCommandExecutors();

        $registeredCommands = [];
        foreach ($registeredCommandExecutors as $commandExecutor) {

            $registeredCommands = array_merge($registeredCommands, $commandExecutor->getRegisteredCommands());
        }

        $output = '';
        foreach ($registeredCommands as $registeredCommand) {
            
            $output .= "Command: {$registeredCommand->getCommand()}" . PHP_EOL;

            foreach ($registeredCommand->getSignature() as $parameter => $description) {

                $output .= "    {$parameter}: {$description}" . PHP_EOL;
            }

            $output .= PHP_EOL;
        }

        $this->basket->getOutput()->writeOutputError($output);
    }
}
