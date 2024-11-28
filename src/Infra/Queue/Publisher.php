<?php

namespace Maruko\DddPhp\Infra\Queue;

use Maruko\DddPhp\Application\Command\Command;

class Publisher
{

    /**
     * @var array<Observer>
     */
    private array $observers;

    public function __construct()
    {
        $this->observers = [];
    }

    public function register(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function publish(Command $command): void
    {
        foreach ($this->observers as $observer) {
            if ($observer->getOperation() === $command->getOperation()) {
                $observer->notify($command);
            }
        }
    }

}