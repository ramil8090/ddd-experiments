<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:50
 */

namespace Blog\Infrastructure\Application\Notification;


use Blog\Application\EventStore;
use Blog\Domain\DomainEvent;
use Blog\Domain\Event\StoredEvent;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;

class DoctrineEventStore extends EntityRepository implements EventStore
{
    private $serializer;

    public function append(DomainEvent $domainEvent)
    {
        $storedEvent = new StoredEvent(
            get_class($domainEvent),
            $domainEvent->occurredOn(),
            $this->serializer()->serialize($domainEvent, 'json')
        );

        $this->getEntityManager()->persist($storedEvent);
        $this->getEntityManager()->flush($storedEvent);
    }

    public function allStoredEventsSince($eventId)
    {
        $query = $this->createQueryBuilder('e');

        if ($eventId) {
            $query->where('e.eventId > :eventId');
            $query->setParameters(array('eventId' => $eventId));
        }

        $query->orderBy('e.eventId');

        return $query->getQuery()->getResult();
    }

    /**
     * @return \JMS\Serializer\SerializerInterface
     */
    private function serializer()
    {
        if (null === $this->serializer) {

            $this->serializer =
                SerializerBuilder::create()
                    ->addMetadataDir(__DIR__ . '/../../../Infrastructure/Application/Serialization/JMS/Config')
                    ->setCacheDir(__DIR__ . '/../../../../var/cache/jms-serializer')
                    ->build()
            ;
        }

        return $this->serializer;
    }
}