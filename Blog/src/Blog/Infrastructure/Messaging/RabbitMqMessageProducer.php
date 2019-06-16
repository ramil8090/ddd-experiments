<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 03.05.19
 * Time: 11:58
 */

namespace Blog\Infrastructure\Messaging;


use Blog\Application\Service\Notification\MessageProducer;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqMessageProducer extends RabbitMqMessaging implements MessageProducer
{
    /**
     * @param $exchangeName
     * @param string $notificationMessage
     * @param string $notificationType
     * @param int $notificationId
     * @param \DateTimeImmutable $notificationOccurredOn
     */
    public function send($exchangeName, $notificationMessage, $notificationType, $notificationId, \DateTimeImmutable $notificationOccurredOn)
    {
        $this->channel($exchangeName)->basic_publish(
            new AMQPMessage(
                $notificationMessage,
                ['type' => $notificationType, 'timestamp' => $notificationOccurredOn->getTimestamp(), 'message_id' => $notificationId]
            ),
            $exchangeName
        );
    }
}
