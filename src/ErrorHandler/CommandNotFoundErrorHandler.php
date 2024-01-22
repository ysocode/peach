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

    public function handle(): void
    {
        $this->basket->getOutput()->writeOutputError(
            "Command not found."
        );
    }
}
