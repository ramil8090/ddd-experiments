<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 14:49
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\DomainEvent;

class PostCreated implements DomainEvent
{
    /**
     * @var \DateTimeImmutable
     */
    private $occurredOn;
    /**
     * @var PostId
     */
    private $postId;

    public function __construct(PostId $postId)
    {
        $this->postId = $postId;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}