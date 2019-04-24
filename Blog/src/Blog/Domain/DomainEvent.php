<?php

namespace Blog\Domain;


interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}