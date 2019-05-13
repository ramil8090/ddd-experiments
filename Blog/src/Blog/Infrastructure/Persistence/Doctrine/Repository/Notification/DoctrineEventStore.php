<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:50
 */

namespace Blog\Infrastructure\Persistence\Doctrine\Repository\Notification;


use Blog\Domain\Event\EventStore;
use Blog\Domain\DomainEvent;
use Blog\Domain\Event\StoredEvent;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class DoctrineEventStore implements EventStore
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function append(DomainEvent $domainEvent)
    {
        $storedEvent = new StoredEvent(
            get_class($domainEvent),
            $domainEvent->occurredOn(),
            $this->serializer()->serialize($domainEvent, 'json')
        );

        $this->em->persist($storedEvent);
        $this->em->flush($storedEvent);
    }

    public function allStoredEventsSince($eventId)
    {
        $query = $this->em->createQueryBuilder();

        $query->select('e');

        $query->from('Blog\Domain\Event\StoredEvent', 'e');

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
                    ->addMetadataDir(__DIR__ . '/../../../../../Infrastructure/Serialization/JMS/Config')
                    ->setCacheDir(__DIR__ . '/../../../../../../var/cache/jms-serializer')
                    ->build()
            ;
        }

        return $this->serializer;
    }
}