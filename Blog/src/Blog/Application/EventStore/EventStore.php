<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 10:11
 */

namespace Blog\Application\EventStore;


use Blog\Domain\DomainEvent;

interface EventStore
{
    public function append(DomainEvent $domainEvent);
    public function allStoredEventsSince($eventId);
}