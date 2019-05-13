<?php

namespace Blog\Domain\Model\Blog;


use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\User\UserId;
use Blog\Domain\Model\Post\Post;
use Blog\Domain\Model\Post\PostId;

class Blog
{
    /**
     * @var BlogId
     */
    private $blogId;
    /**
     * @var UserId
     */
    private $userId;
    /**
     * @var Title
     */
    private $title;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    public function __construct(
        BlogId $blogId,
        UserId $userId,
        Title $title
    )
    {
        $this->blogId = $blogId;
        $this->userId = $userId;
        $this->title = $title;

        $this->status = Status::active();
        $this->createdAt = new \DateTimeImmutable();

        DomainEventPublisher::instance()->publish(new BlogCreated(
            $blogId
        ));
    }

    public function isActive(): bool
    {
        return $this->status->equal(Status::active());
    }

    public function creationDate(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function delete(): void
    {
        $this->status = Status::deleted();

        DomainEventPublisher::instance()->publish(new BlogDeleted(
            $this->blogId
        ));
    }

    public function isDeleted(): bool
    {
        return $this->status->equal(Status::deleted());
    }

    public function isOwnedBy(UserId $userId): bool
    {
        return $this->userId->getId() === $userId->getId();
    }

    public function createPost(PostId $postId, \Blog\Domain\Model\Post\Title $title, $body): Post
    {
        if ($this->isDeleted()) {
            throw new \DomainException("Can't create post in deleted blog.");
        }

        return new Post(
            $postId,
            $this->blogId,
            $this->userId,
            $title,
            $body
        );
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function blogId(): BlogId
    {
        return $this->blogId;
    }
}