<?php

namespace YSOCode\Peach\ErrorHandler\Interfaces;

use YSOCode\Peach\Basket;

interface ErrorHandlerInterface
{
    /**
     * Create a new ErrorHandler instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket);

    /**
     * Handle the error.
     *
     * @return void
     */
    public function handle(): void;
}