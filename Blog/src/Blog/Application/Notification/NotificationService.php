<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:22
 */

namespace Blog\Application\Notification;




use Blog\Domain\Event\EventStore;
use Blog\Domain\Event\StoredEvent;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class NotificationService
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var EventStore
     */
    private $eventStore;
    /**
     * @var PublishedMessageTracker
     */
    private $publishedMessageTracker;
    /**
     * @var MessageProducer
     */
    private $messageProducer;

    public function __construct(
        EventStore $anEventStore,
        PublishedMessageTracker $aPublishedMessageTracker,
        MessageProducer $aMessageProducer
    )
    {
        $this->eventStore = $anEventStore;
        $this->publishedMessageTracker = $aPublishedMessageTracker;
        $this->messageProducer = $aMessageProducer;
    }

    /**
     * @return int
     */
    public function publishNotifications($exchangeName)
    {
        $publishedMessageTracker = $this->publishedMessageTracker();

        $notifications = $this->listUnpublishedNotifications(
            $publishedMessageTracker->mostRecentPublishedMessageId($exchangeName)
        );

        if (!$notifications) {
            return 0;
        }

        $messageProducer = $this->messageProducer();

        $messageProducer->open($exchangeName);

        try {
            $publishedMessages = 0;
            $lastPublishedNotification = null;
            foreach ($notifications as $notification) {
                $lastPublishedNotification = $this->publish($exchangeName, $notification, $messageProducer);
                $publishedMessages++;
            }
        } catch(\Exception $e) {
        }

        $this->trackMostRecentPublishedMessage($publishedMessageTracker, $exchangeName, $lastPublishedNotification);

        $messageProducer->close($exchangeName);

        return $publishedMessages;
    }

    /**
     * @return PublishedMessageTracker
     */
    protected function publishedMessageTracker()
    {
        return $this->publishedMessageTracker;
    }

    /**
     * @param $mostRecentPublishedMessageId
     * @return StoredEvent[]
     */
    private function listUnpublishedNotifications($mostRecentPublishedMessageId)
    {
        $storeEvents = $this->eventStore()->allStoredEventsSince($mostRecentPublishedMessageId);
        return $storeEvents;
    }

    /**
     * @return EventStore
     */
    protected function eventStore()
    {
        return $this->eventStore;
    }

    private function messageProducer()
    {
        return $this->messageProducer;
    }

    private function publish($exchangeName, StoredEvent $notification, MessageProducer $messageProducer)
    {
        $messageProducer->send(
            $exchangeName,
            $this->serializer()->serialize($notification, 'json'),
            $notification->typeName(),
            $notification->eventId(),
            $notification->occurredOn()
        );
        return $notification;
    }

    /**
     * @return \JMS\Serializer\SerializerInterface
     */
    private function serializer()
    {
        if (null === $this->serializer) {
            $this->serializer =
                SerializerBuilder::create()
                    ->addMetadataDir(__DIR__ . '/../../Infrastructure/Application/Serialization/JMS/Config')
                    ->setCacheDir(__DIR__ . '/../../../var/cache/jms-serializer')
                    ->build();
        }
        return $this->serializer;
    }

    private function trackMostRecentPublishedMessage(PublishedMessageTracker $publishedMessageTracker, $exchangeName, $notification)
    {
        $publishedMessageTracker->trackMostRecentPublishedMessage($exchangeName, $notification);
    }
}