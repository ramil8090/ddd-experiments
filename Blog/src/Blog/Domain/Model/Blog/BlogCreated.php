<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 14:49
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\DomainEvent;

class BlogCreated implements DomainEvent
{
    /**
     * @var \DateTimeImmutable
     */
    private $occurredOn;
    /**
     * @var BlogId
     */
    private $blogId;

    public function __construct(BlogId $blogId)
    {
        $this->blogId = $blogId;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function blogId(): BlogId
    {
        return $this->blogId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}