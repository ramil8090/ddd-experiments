<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:56
 */

namespace Blog\Infrastructure\Application\Notification;


use Blog\Application\Notification\PublishedMessageTracker;
use Doctrine\ORM\EntityRepository;

class DoctrinePublishedMessageTracker extends EntityRepository implements PublishedMessageTracker
{
    /**
     * @param $aTypeName
     * @return int
     */
    public function mostRecentPublishedMessageId($aTypeName)
    {
        $messageTracked = $this->findOneByTypeName($aTypeName);

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

        $publishedMessage = $this->find($aTypeName);
        if (!$publishedMessage) {
            $publishedMessage = new PublishedMessage(
                $aTypeName,
                $maxId
            );
        }

        $publishedMessage->updateMostRecentPublishedMessageId($maxId);

        $this->getEntityManager()->persist($publishedMessage);
        $this->getEntityManager()->flush($publishedMessage);
    }
}
