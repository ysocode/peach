<?php

namespace YSOCode\Peach\Traits;

use YSOCode\Peach\Basket;

trait CommandExecutorTrait
{
    /**
     * Booted Basket instances.
     *
     * @var Basket $basket
     */
    protected Basket $basket;

    /**
     * Whether the commands have been attached.
     *
     * @var bool $attached
     */
    protected bool $attached = false;

    /**
     * Create a new CommandCollection instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function markAsAttached()
    {
        $this->attached = true;
    }
}
