<?php

namespace Blog\Domain\Model\Common;

use Blog\Domain\Model\DomainEvent;
use Blog\Domain\Model\DomainEventSubscriber;

class SpySubscriber implements DomainEventSubscriber
{
    /**
     * @var DomainEvent
     */
    public $domainEvent;


    public function handle(DomainEvent $domainEvent): void
    {
        $this->domainEvent = $domainEvent;
    }

    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return true;
    }
}