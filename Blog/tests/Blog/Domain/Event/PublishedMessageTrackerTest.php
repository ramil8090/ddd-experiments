<?php

namespace Blog\Domain\Event;

use Blog\Application\Notification\PublishedMessageTracker;
use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\Blog\BlogBuilder;
use Blog\Domain\PersistDomainEventSubscriber;
use PHPUnit\Framework\TestCase;

class PublishedMessageTrackerTest extends TestCase
{
    use CreateDoctrineEventRepositoriesTrait;

    /**
     * @var PublishedMessageTracker
     */
    private $publishedMessageTracker;
    /**
     * @var EventStore
     */
    private $storedEventRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publishedMessageTracker = $this->createPublishedMessageRepository();

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

        $lastStoredEvent = $storedEvents[0];

        $this->publishedMessageTracker->trackMostRecentPublishedMessage(
            $typeName = 'a_type_name',
            $storedEvents[0]
        );

        $id = $this->publishedMessageTracker->mostRecentPublishedMessageId($typeName);

        $this->assertEquals($id, $lastStoredEvent->eventId());
    }
}