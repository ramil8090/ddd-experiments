<?php

namespace Blog\Domain\Model\Post;


use Assert\Assertion;
use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\DomainEventPublisher;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Member\Author;
use Blog\Domain\Model\Member\Moderator;
use Blog\Domain\Model\Member\Owner;

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
     * Owner
     */
    private $owner;
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

    protected function __construct(
        PostId $postId,
        BlogId $blogId,
        Owner $owner,
        Author $author,
        Title $title,
        string $content
    )
    {
        $this->postId = $postId;
        $this->blogId = $blogId;
        $this->owner = $owner;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;

        $this->status = Status::newPost();

        DomainEventPublisher::instance()->publish(new PostCreated(
            $postId
        ));
    }

    public static function create(
        PostId $postId,
        Blog $blog,
        Owner $owner,
        Author $author,
        Title $title,
        string $content
    ): Post
    {

        if (!$blog->hasAuthor($author)) {
            throw new \DomainException("Author not found");
        }

        return new Post(
            $postId,
            $blog->blogId(),
            $owner,
            $author,
            $title,
            $content
        );
    }

    public function approve(Moderator $moderator): void
    {
        $this->assertNotRejected();
        $this->assertNotDeleted();

        $this->status = Status::approved();

        DomainEventPublisher::instance()->publish(new PostApproved(
            $this->postId,
            $moderator
        ));
    }

    public function reject(Moderator $moderator): void
    {
        $this->assertNotRejected();
        $this->assertNotDeleted();

        $this->status = Status::rejected();

        DomainEventPublisher::instance()->publish(new PostRejected(
            $this->postId,
            $moderator
        ));
    }

    private function assertNotRejected(): void
    {
        if ($this->status->equals(Status::rejected())) {
            throw new \DomainException("Can't reject rejected post");
        }
    }

    private function assertNotDeleted(): void
    {
        if ($this->status->equals(Status::deleted())) {
            throw new \DomainException("Can't reject deleted post");
        }
    }

    public function deleteByAuthor(Author $author): void
    {
        $this->assertAuthor($author);

        $this->delete();
    }

    public function deleteByOwner(Owner $owner): void
    {
        $this->assertOwner($owner);

        $this->delete();
    }

    private function assertAuthor(Author $author): void
    {
        if ($this->author->equals($author)) {
            return;
        }

        throw new \DomainException("Wrong post author.");
    }

    private function assertOwner(Owner $owner): void
    {
        if($this->owner->equals($owner)) {
            return;
        }

        throw new \DomainException("Wrong post owner.");
    }

    public function deleteByModerator(Moderator $moderator): void
    {
        $this->delete();
    }

    protected function delete(): void
    {
        if ($this->status->equals(Status::deleted())) {
            throw new \DomainException('A post is already deleted.');
        }

        $this->status = Status::deleted();

        DomainEventPublisher::instance()->publish(new PostDeleted(
            $this->postId
        ));
    }

    public function updateTitleByAuthor(Author $author, Title $title): void
    {
        $this->assertAuthor($author);

        $this->updateTitle($title);
    }

    public function updateTitleByOwner(Owner $owner, Title $title): void
    {
        $this->assertOwner($owner);

        $this->updateTitle($title);
    }

    public function updateTitleByModerator(Moderator $moderator, Title $title): void
    {
        Assertion::notNull($moderator);

        $this->updateTitle($title);
    }

    protected function updateTitle($title): void
    {
        Assertion::notEmpty($title);

        $this->title = $title;

        DomainEventPublisher::instance()->publish(new PostTitleUpdated(
            $this->postId,
            $title
        ));
    }

    public function updateContentByOwner(Owner $owner, string $content): void
    {
        $this->assertOwner($owner);

        $this->updateContent($content);
    }

    public function updateContentByAuthor(Author $author, string $content): void
    {
        $this->assertAuthor($author);

        $this->updateContent($content);
    }

    public function updateContentByModerator(Moderator $moderator, string $content): void
    {
        Assertion::notNull($moderator);

        $this->updateContent($content);
    }

    protected function updateContent(string $content): void
    {
        Assertion::notEmpty($content);

        $this->content = $content;

        DomainEventPublisher::instance()->publish(new PostContentUpdated(
            $this->postId,
            $content
        ));
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

    public function owner(): Owner
    {
        return $this->owner;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function status(): Status
    {
        return $this->status;
    }
}