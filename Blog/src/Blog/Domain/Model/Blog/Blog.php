<?php

namespace Blog\Domain\Model\Blog;


use Blog\Domain\AggregateRoot;
use Blog\Domain\EventTrait;
use Blog\Domain\Model\Member\Author;
use Blog\Domain\Model\Member\Owner;
use Blog\Domain\Model\Post\Post;
use Blog\Domain\Model\Post\PostId;

class Blog implements AggregateRoot
{
    use EventTrait;

    /**
     * @var BlogId
     */
    private $blogId;
    /**
     * @var Owner
     */
    private $owner;
    /**
     * @var Title
     */
    private $title;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var array
     */
    private $authors;

    public function __construct(
        BlogId $blogId,
        Owner $owner,
        Title $title
    )
    {
        $this->blogId = $blogId;
        $this->owner = $owner;
        $this->title = $title;
        $this->status = Status::active();
        $this->authors = [];

        $this->recordEvent(new BlogCreated(
            $blogId
        ));
    }

    public function isActive(): bool
    {
        return $this->status->equal(Status::active());
    }

    public function isArchived(): bool
    {
        return $this->status->equal(Status::archived());
    }

    public function archive(): void
    {
        if ($this->status->equal(Status::archived())) {
            throw new \DomainException('A blog is already archived.');
        }

        $this->status = Status::archived();

        $this->recordEvent(new BlogArchived(
            $this->blogId
        ));
    }

    public function restore(): void
    {
        if ($this->status->equal(Status::active())) {
            throw new \DomainException('A blog is already active.');
        }

        $this->status = Status::active();

        $this->recordEvent(new BlogRestored(
           $this->blogId
        ));
    }

    public function appendAuthor(Author $author): void
    {
        if ($this->hasAuthor($author)) {
            throw new \DomainException('An author is already exists.');
        }

        $this->authors[] = $author;

        $this->recordEvent(new BlogAuthorAppended(
            $this->blogId,
            $author
        ));
    }

    public function detachAuthor(Author $author): void
    {
        $index = array_search($author, $this->authors);
        if ( $index !== false ) {

            unset( $this->authors[$index] );

            $this->recordEvent(new BlogAuthorDetached(
                $this->blogId,
                $author
            ));
        }
    }

    public function hasAuthor(Author $author): bool
    {
        $author = array_filter($this->authors, function ($currentAuthor) use ($author) {
            if ($currentAuthor->equal($author)) {
                return $currentAuthor;
            }
        });

        return $author != null;
    }

    public function createPost(
        PostId $postId,
        \Blog\Domain\Model\Post\Title $title,
        string $content,
        Author $author
    ): Post
    {
        if ($this->isArchived()) {
            throw new \DomainException("Error create post. A blog is archived.");
        }

        if (!$this->hasAuthor($author)) {
            throw new \DomainException("An author not found.");
        }

        return new Post(
            $postId,
            $this->blogId,
            $author,
            $title,
            $content
        );
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function blogId(): BlogId
    {
        return $this->blogId;
    }

    public function authors(): array
    {
        return $this->authors;
    }

    public function owner(): Owner
    {
        return $this->owner;
    }
}