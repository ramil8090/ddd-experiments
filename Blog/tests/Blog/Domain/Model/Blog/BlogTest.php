<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 17:45
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\Common\SpySubscriber;
use Blog\Domain\Model\Common\UserId;
use Blog\Domain\Model\Post\PostId;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    public function testANewBlogIsActiveByDefault()
    {
        $aBlog = new Blog(
            new BlogId(1),
            new UserId(1),
            new Title('A Blog Title')
        );

        $this->assertTrue($aBlog->isActive());
        $this->assertInstanceOf('DateTimeImmutable', $aBlog->creationDate());
    }

    public function testOnCreateItShouldPublishBlogCreatedEvent()
    {
        $subscriber = new SpySubscriber();
        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(BlogCreated::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->blogId()->equals($blogId));
    }

    public function testABlogCanBeDelete()
    {
        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->delete();

        $this->assertTrue($aBlog->isDeleted());
    }

    public function testOnDeleteItShouldPublishBlogDeletedEvent()
    {
        $subscriber = new SpySubscriber();

        $blog = BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();
        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        $blog->delete();

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(BlogDeleted::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->blogId()->equals($blogId));
    }

    public function testABlogHasOwner()
    {
        $aBlog = BlogBuilder::aBlog()
            ->withUserId($user = new UserId(1))
            ->build();

        $this->assertTrue($aBlog->isOwnedBy($user));
        $this->assertFalse($aBlog->isOwnedBy(new UserId(100)));
    }

    public function testCanCreatePost()
    {
        $aBlog = BlogBuilder::aBlog()->build();

        $aPost = $aBlog->createPost(
            $postId = new PostId(1),
            $title = new \Blog\Domain\Model\Post\Title('A Post Title'),
            $body = 'A Post Body'
        );

        $this->assertTrue($aPost->postId()->equals($postId));
        $this->assertEquals($aPost->title(), $title);
        $this->assertEquals($aPost->body(), $body);
    }

    public function testCantCreatePostIfBlogIsDeleted()
    {
        $this->expectExceptionMessage("Can't create post in deleted blog.");

        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->delete();

        $aBlog->createPost(
            $postId = new PostId(1),
            $title = new \Blog\Domain\Model\Post\Title('A Post Title'),
            $body = 'A Post Body'
        );
    }
}