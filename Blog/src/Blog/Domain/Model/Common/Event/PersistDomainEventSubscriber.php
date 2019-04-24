<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 12:18
 */

namespace Blog\Domain\Model\Common\Event;


use Blog\Domain\DomainEvent;
use Blog\Domain\DomainEventSubscriber;

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