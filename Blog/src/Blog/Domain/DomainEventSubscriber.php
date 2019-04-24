<?php

namespace Blog\Domain;


interface DomainEventSubscriber
{
    public function handle(DomainEvent $domainEvent): void;

    public function isSubscribedTo(DomainEvent $domainEvent): bool;
}