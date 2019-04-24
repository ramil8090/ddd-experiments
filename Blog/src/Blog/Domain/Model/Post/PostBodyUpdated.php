<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 14:49
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\DomainEvent;

class PostBodyUpdated implements DomainEvent
{
    /**
     * @var \DateTimeImmutable
     */
    private $occurredOn;
    /**
     * @var PostId
     */
    private $postId;
    /**
     * @var string
     */
    private $body;


    public function __construct(PostId $postId, string $body)
    {
        $this->postId = $postId;
        $this->body = $body;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}