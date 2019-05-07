<?php

namespace Blog\Domain\Event;

use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\Blog\BlogBuilder;
use Blog\Domain\PersistDomainEventSubscriber;
use PHPUnit\Framework\TestCase;

class PersistDomainEventTest extends TestCase
{
    use CreateDoctrineEventRepositoriesTrait;

    /**
     * @var EventStore
     */
    private $storedEventRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storedEventRepository = $this->createStoredEventRepository();
    }

    public function testItShouldPersistDomainEvent()
    {
        $id = DomainEventPublisher::instance()->subscribe(
            new PersistDomainEventSubscriber($this->storedEventRepository)
        );

        BlogBuilder::aBlog()->build();

        DomainEventPublisher::instance()->unsubscribe($id);

        $storedEvents = $this->storedEventRepository->allStoredEventsSince(0);

        $this->assertCount(1, $storedEvents);
    }
}