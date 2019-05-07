<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 12:18
 */

namespace Blog\Domain;


use Blog\Domain\Event\EventStore;

class PersistDomainEventSubscriber implements DomainEventSubscriber
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
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