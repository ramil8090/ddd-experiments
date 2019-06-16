<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 15:07
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\DomainEvent;
use Blog\Domain\Model\Member\Moderator;

class PostApproved implements DomainEvent
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
     * @var Moderator
     */
    private $moderator;

    public function __construct(PostId $postId, Moderator $moderator)
    {
        $this->postId = $postId;
        $this->occurredOn = new \DateTimeImmutable();
        $this->moderator = $moderator;
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function moderator(): Moderator
    {
        return $this->moderator;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}