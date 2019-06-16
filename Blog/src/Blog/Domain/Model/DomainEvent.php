<?php

namespace Blog\Domain\Model;


interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}