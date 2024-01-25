<?php

namespace YSOCode\Peach\Executors\Traits;

use YSOCode\Peach\Basket;

trait CommandExecutorTrait
{
    /**
     * Basket instances.
     *
     * @var Basket $basket
     */
    protected Basket $basket;

    /**
     * Indicates if the commands have been attached.
     *
     * @var bool $attached
     */
    protected bool $attached = false;

    /**
     * Create a new CommandExecutor instance.
     *
     * @param Basket $basket
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    /**
     * Mark the commands as attached.
     *
     * @return void
     */
    public function markAsAttached(): void
    {
        $this->attached = true;
    }

    /**
     * Indicates if the commands have been attached.
     *
     * @return bool
     */
    public function isAttached(): bool
    {
        return $this->attached;
    }
}
