<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:56
 */

namespace Blog\Infrastructure\Notification;


use Blog\Application\Service\Notification\PublishedMessageTracker;
use Blog\Domain\Event\PublishedMessage;
use Blog\Domain\Event\StoredEvent;
use Doctrine\ORM\EntityManager;

class DoctrinePublishedMessageTracker implements PublishedMessageTracker
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $aTypeName
     * @return int
     */
    public function mostRecentPublishedMessageId($aTypeName)
    {
        $query = $this->em->createQueryBuilder();

        $messageTracked = $query
            ->select('p')
            ->from('Blog\Domain\Event\PublishedMessage', 'p')
            ->where('p.typeName = :typeName')
            ->setParameter(':typeName', $aTypeName)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$messageTracked) {
            return null;
        }

        return $messageTracked->mostRecentPublishedMessageId();
    }

    /**
     * @param $aTypeName
     * @param StoredEvent $notification
     */
    public function trackMostRecentPublishedMessage($aTypeName, $notification)
    {
        if (!$notification) {
            return;
        }

        $maxId = $notification->eventId();

        $query = $this->em->createQueryBuilder();

        $publishedMessage = $query
            ->select('p')
            ->from('Blog\Domain\Event\PublishedMessage', 'p')
            ->where('p.typeName = :typeName')
            ->setParameter(':typeName', $aTypeName)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$publishedMessage) {
            $publishedMessage = new PublishedMessage(
                $aTypeName,
                $maxId
            );
        }

        $publishedMessage->updateMostRecentPublishedMessageId($maxId);

        $this->em->persist($publishedMessage);
        $this->em->flush($publishedMessage);
    }
}
