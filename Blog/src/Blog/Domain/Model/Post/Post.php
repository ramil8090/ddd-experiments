<?php

namespace Blog\Domain\Model\Post;


use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Common\UserId;

class Post
{
    /**
     * @var PostId
     */
    private $postId;
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
     * @var string
     */
    private $body;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;
    /**
     * @var \DateTimeImmutable
     */
    private $publishedAt;


    public function __construct(
        PostId $postId,
        BlogId $blogId,
        UserId $userId,
        Title $title,
        string $body)
    {
        $this->postId = $postId;
        $this->blogId = $blogId;
        $this->userId = $userId;
        $this->title = $title;
        $this->body = $body;

        $this->status = Status::recent();
        $this->createdAt = new \DateTimeImmutable();


        DomainEventPublisher::instance()->publish(new PostCreated(
            $postId
        ));
    }

    public function publish(): void
    {
        if ($this->status->equal(Status::deleted())) {
            throw new \DomainException('A post is deleted.');
        }

        if ($this->status->equal(Status::published())) {
            throw new \DomainException('A post is already published.');
        }

        $this->status = Status::published();
        $this->publishedAt = new \DateTimeImmutable();

        DomainEventPublisher::instance()->publish(new PostPublished(
            $this->postId
        ));
    }

    public function delete(): void
    {
        if ($this->status->equal(Status::deleted())) {
            throw new \DomainException('A post is already deleted.');
        }

        $this->status = Status::deleted();

        DomainEventPublisher::instance()->publish(new PostDeleted(
            $this->postId
        ));
    }

    public function isDeleted(): bool
    {
        return $this->status->equal(Status::deleted());
    }

    public function publicationDate(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function isPublished(): bool
    {
        return $this->status->equal(Status::published());
    }

    public function updateTitle(Title $title): void
    {
        $this->title = $title;

        DomainEventPublisher::instance()->publish(new PostTitleUpdated(
            $this->postId,
            $title
        ));
    }

    public function updateBody(string $body): void
    {
        $this->body = $body;

        DomainEventPublisher::instance()->publish(new PostBodyUpdated(
            $this->postId,
            $body
        ));
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function isAuthor(UserId $userId): bool
    {
        return $this->userId->getId() === $userId->getId();
    }

    public function belongsTo(BlogId $blogId): bool
    {
        return $this->blogId->getId() === $blogId->getId();
    }
}