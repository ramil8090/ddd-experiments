<?php

namespace Blog\Domain\Model\Post;


use Blog\Domain\AggregateRoot;
use Blog\Domain\DomainEventPublisher;
use Blog\Domain\EventTrait;
use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Member\Author;

class Post implements AggregateRoot
{
    use EventTrait;

    /**
     * @var PostId
     */
    private $postId;
    /**
     * @var BlogId
     */
    private $blogId;
    /**
     * @var Author
     */
    private $author;
    /**
     * @var Title
     */
    private $title;
    /**
     * @var string
     */
    private $content;
    /**
     * @var Status
     */
    private $status;


    public function __construct(
        PostId $postId,
        BlogId $blogId,
        Author $author,
        Title $title,
        string $content)
    {
        $this->postId = $postId;
        $this->blogId = $blogId;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;

        $this->status = Status::newPost();

        $this->recordEvent(new PostCreated(
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

    public function updateTitle(Title $title): void
    {
        $this->title = $title;

        DomainEventPublisher::instance()->publish(new PostTitleUpdated(
            $this->postId,
            $title
        ));
    }

    public function updateBody(string $content): void
    {
        $this->content = $content;

        DomainEventPublisher::instance()->publish(new PostBodyUpdated(
            $this->postId,
            $content
        ));
    }

    public function isAuthor(Author $author): bool
    {
        return $this->author->equal($author);
    }

    public function belongsTo(BlogId $blogId): bool
    {
        return $this->blogId->getId() === $blogId->getId();
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function blogId(): BlogId
    {
        return $this->blogId;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function title(): Title
    {
        return $this->title;
    }

}