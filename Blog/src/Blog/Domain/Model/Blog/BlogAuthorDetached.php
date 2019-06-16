<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 22.04.19
 * Time: 14:49
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\Model\DomainEvent;
use Blog\Domain\Model\Member\Author;

class BlogAuthorDetached implements DomainEvent
{
    /**
     * @var \DateTimeImmutable
     */
    private $occurredOn;
    /**
     * @var BlogId
     */
    private $blogId;
    /**
     * @var Author
     */
    private $author;

    public function __construct(BlogId $blogId, Author $author)
    {
        $this->blogId = $blogId;
        $this->author = $author;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function blogId(): BlogId
    {
        return $this->blogId;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}