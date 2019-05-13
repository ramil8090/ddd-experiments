<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:28
 */

namespace Blog\Application\Service\Notification;


interface MessageProducer
{
    /**
     * @param string $exchangeName
     * @return mixed
     */
    public function open($exchangeName);

    /**
     * @param $exchangeName
     * @param string $notificationMessage
     * @param string $notificationType
     * @param int $notificationId
     * @param \DateTimeImmutable $notificationOccurredOn
     * @return
     */
    public function send(
        $exchangeName,
        $notificationMessage,
        $notificationType,
        $notificationId,
        \DateTimeImmutable $notificationOccurredOn
    );

    /**
     * @param string $exchangeName
     * @return mixed
     */
    public function close($exchangeName);
}