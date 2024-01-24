<?php

namespace YSOCode\Peach\ErrorHandler;

use YSOCode\Peach\Basket;
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

    /**
     * Handle the error.
     *
     * @return void
     */
    public function handle(): void
    {
        $registeredCommandExecutors = $this->basket->getRegisteredCommandExecutors();

        $registeredCommands = [];
        foreach ($registeredCommandExecutors as $commandExecutor) {

            $registeredCommands = array_merge($registeredCommands, $commandExecutor->getRegisteredCommands());
        }

        foreach ($registeredCommands as $registeredCommand) {
            
            $this->basket->getOutput()->writeError("Command: " . $registeredCommand->getCommand());

            foreach ($registeredCommand->getSignature() as $parameter => $description) {

                $this->basket->getOutput()->writeError("  {$parameter}: {$description}");
            }
        }

        $this->basket->getOutput()->outputError();
    }
}
