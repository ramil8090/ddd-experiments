<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:26
 */

namespace Blog\Application\Notification;


use Blog\Domain\Model\Common\Event\StoredEvent;

interface PublishedMessageTracker
{
    /**
     * @param $aTypeName
     * @return int
     */
    public function mostRecentPublishedMessageId($aTypeName);
    /**
     * @param $aTypeName
     * @param StoredEvent $notification
     */
    public function trackMostRecentPublishedMessage($aTypeName, $notification);
}