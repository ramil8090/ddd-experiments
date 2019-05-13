<?php

namespace Blog\Domain\Model\Post;

use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Common\SpySubscriber;
use Blog\Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testANewPostIsNotPublishedByDefault()
    {
        $aPost = PostBuilder::aPost()->build();

        $this->assertFalse($aPost->isPublished());

        $this->assertNull($aPost->publicationDate());
    }

    public function testAPostCanBePublishedWithPublicationDate()
    {
        $aPost = PostBuilder::aPost()->build();

        $aPost->publish();

        $this->assertTrue($aPost->isPublished());

        $this->assertInstanceOf('DateTimeImmutable', $aPost->publicationDate());
    }

    public function testAPostCanBeDeleted()
    {
        $aPost = PostBuilder::aPost()->build();

        $aPost->delete();

        $this->assertTrue($aPost->isDeleted());
    }

    public function testAPostCantBeDeletedIfAlreadyDeleted()
    {
        $this->expectExceptionMessage('A post is already deleted.');

        $aPost = PostBuilder::aPost()->build();

        $aPost->delete();

        $aPost->delete();
    }

    public function testAPostCantBePublishedIfAlreadyDeleted()
    {
        $this->expectExceptionMessage('A post is deleted.');

        $aPost = PostBuilder::aPost()->build();

        $aPost->delete();

        $aPost->publish();
    }

    public function testAPostCantBePublishedIfAlreadyPublished()
    {
        $this->expectExceptionMessage('A post is already published.');

        $aPost = PostBuilder::aPost()->build();

        $aPost->publish();

        $aPost->publish();
    }

    public function testAPostTitleCanBeUpdated()
    {
        $aPost = PostBuilder::aPost()->build();

        $aPost->updateTitle(
            $newTitle = new Title('A New Title')
        );

        $this->assertEquals($newTitle, $aPost->title());
    }

    public function testAPostBodyCanBeUpdated()
    {
        $aPost = PostBuilder::aPost()->build();

        $aPost->updateBody(
            $newBody = 'A New Body Of Post'
        );

        $this->assertEquals($newBody, $aPost->body());
    }

    public function testAPostHasAnAuthor()
    {
        $aPost = PostBuilder::aPost()
            ->withUserId($userId = new UserId(1))
            ->build();

        $this->assertTrue($aPost->isAuthor($userId));
        $this->assertFalse($aPost->isAuthor(new UserId(100)));
    }

    public function testAPostBelongsToBlog()
    {
        $aPost = PostBuilder::aPost()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        $this->assertTrue($aPost->belongsTo($blogId));
        $this->assertFalse($aPost->belongsTo(new BlogId(100)));
    }

    public function testOnCreateItShouldPublishPostCreatedEvent()
    {
        $subscriber = new SpySubscriber();
        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        PostBuilder::aPost()
            ->withPostId($postId = new PostId(1))
            ->build();

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(PostCreated::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
    }

    public function testOnPublishItShouldPublishPostPublishedEvent()
    {
        $subscriber = new SpySubscriber();

        $aPost = PostBuilder::aPost()
            ->withPostId($postId = new PostId(1))
            ->build();

        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        $aPost->publish();

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(PostPublished::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
    }

    public function testOnDeleteItShouldPublishPostDeletedEvent()
    {
        $subscriber = new SpySubscriber();

        $aPost = PostBuilder::aPost()
            ->withPostId($postId = new PostId(1))
            ->build();

        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        $aPost->delete();

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(PostDeleted::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
    }

    public function testOnUpdateTitleItShouldPublishPostTitleUpdateEvent()
    {
        $subscriber = new SpySubscriber();

        $aPost = PostBuilder::aPost()
            ->withPostId($postId = new PostId(1))
            ->build();

        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        $aPost->updateTitle($title = new Title('A New Post Title'));

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(PostTitleUpdated::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
        $this->assertEquals($subscriber->domainEvent->title(), $title);
    }

    public function testOnUpdateTitleItShouldPublishPostBodyUpdateEvent()
    {
        $subscriber = new SpySubscriber();

        $aPost = PostBuilder::aPost()
            ->withPostId($postId = new PostId(1))
            ->build();

        $id = DomainEventPublisher::instance()->subscribe($subscriber);

        $aPost->updateBody($body = 'A New Post Body');

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(PostBodyUpdated::class, $subscriber->domainEvent);
        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
        $this->assertEquals($subscriber->domainEvent->body(), $body);
    }
}