<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 10:52
 */

namespace Blog\Domain\Model\Common\Event;


use Blog\Domain\DomainEvent;

class StoredEvent implements DomainEvent
{
    /**
     * @var int
     */
    private $eventId;
    /**
     * @var string
     */
    private $eventBody;
    /**
     * @var \DateTimeImmutable
     */
    private $occurredOn;
    /**
     * @var string
     */
    private $typeName;
    /**
     * @param string $typeName
     * @param \DateTime $occurredOn
     * @param string $eventBody
     */
    public function __construct($typeName, \DateTime $occurredOn, $eventBody)
    {
        $this->eventBody = $eventBody;
        $this->typeName = $typeName;
        $this->occurredOn = $occurredOn;
    }
    /**
     * @return string
     */
    public function eventBody(): string
    {
        return $this->eventBody;
    }
    /**
     * @return int
     */
    public function eventId(): int
    {
        return $this->eventId;
    }
    /**
     * @return string
     */
    public function typeName(): string
    {
        return $this->typeName;
    }
    /**
     * @return \DateTimeImmutable
     */
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}