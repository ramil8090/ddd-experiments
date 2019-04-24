<?php

namespace Blog\Infrastructure\Domain;


use Blog\Domain\DomainEvent;
use Blog\Domain\DomainEventSubscriber;
use Blog\Domain\Model\Common\Event\EventStore;

class PersistDomainEventSubscriber implements DomainEventSubscriber
{
    private $eventStore;

    public function __construct(EventStore $anEventStore)
    {
        $this->eventStore = $anEventStore;
    }

    public function handle(DomainEvent $domainEvent): void
    {
        $this->eventStore->append($domainEvent);
    }

    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return true;
    }
}