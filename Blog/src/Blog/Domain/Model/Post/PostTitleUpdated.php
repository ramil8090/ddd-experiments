<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 14:49
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\DomainEvent;

class PostTitleUpdated implements DomainEvent
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
     * @var Title
     */
    private $title;


    public function __construct(PostId $postId, Title $title)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}