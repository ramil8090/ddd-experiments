<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 07.06.19
 * Time: 16:21
 */

namespace Blog\Domain\Model;


trait EventTrait
{
    private $events = [];

    protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}