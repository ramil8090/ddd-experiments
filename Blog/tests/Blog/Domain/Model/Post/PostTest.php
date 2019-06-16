<?php

namespace Blog\Domain\Model\Post;

use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\DomainEventPublisher;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Common\SpySubscriber;
use Blog\Domain\Model\Member\Author;
use Blog\Domain\Model\Member\Moderator;
use Blog\Domain\Model\Member\Owner;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testCreateANewPostSuccessfully()
    {
        $blog = new Blog(
            new BlogId(1),
            $owner = new Owner(
                'owner_username',
                'owner@email.com',
                'Owner Full Name'
            ),
            new \Blog\Domain\Model\Blog\Title('A Blog Title')
        );

        $blog->appendAuthor($author = new Author(
            'author_username',
            'author@email.com',
            'Author Full Name'
        ));

        $aPost = PostBuilder::aPost()
            ->withPostId($postId = new PostId(1))
            ->withBlog($blog)
            ->withAuthor($author)
            ->withTitle($title = new Title('A Post Title'))
            ->withContent($content = "A Post Content")
            ->build();

        $this->assertTrue($aPost->status()->equals(Status::newPost()));
        $this->assertTrue($postId->equals($aPost->postId()));
        $this->assertEquals($owner, $aPost->owner());
        $this->assertEquals($author, $aPost->author());
        $this->assertEquals($title, $aPost->title());
        $this->assertEquals($content, $aPost->content());
    }

    public function testAPostCanBeApprovedByModerator()
    {
        $aPost = PostBuilder::aPost()->build();
        $moderator = new Moderator(
            'moderator_username',
            'moderator@email.com',
            'Moderator Full Name'
        );

        $aPost->publish();

        $this->assertTrue($aPost->isPublished());

        $this->assertInstanceOf('DateTimeImmutable', $aPost->publicationDate());
    }

//    public function testAPostCanBeDeleted()
//    {
//        $aPost = PostBuilder::aPost()->build();
//
//        $aPost->delete();
//
//        $this->assertTrue($aPost->isDeleted());
//    }
//
//    public function testAPostCantBeDeletedIfAlreadyDeleted()
//    {
//        $this->expectExceptionMessage('A post is already deleted.');
//
//        $aPost = PostBuilder::aPost()->build();
//
//        $aPost->delete();
//
//        $aPost->delete();
//    }
//
//    public function testAPostCantBePublishedIfAlreadyDeleted()
//    {
//        $this->expectExceptionMessage('A post is deleted.');
//
//        $aPost = PostBuilder::aPost()->build();
//
//        $aPost->delete();
//
//        $aPost->publish();
//    }
//
//    public function testAPostCantBePublishedIfAlreadyPublished()
//    {
//        $this->expectExceptionMessage('A post is already published.');
//
//        $aPost = PostBuilder::aPost()->build();
//
//        $aPost->publish();
//
//        $aPost->publish();
//    }
//
//    public function testAPostTitleCanBeUpdated()
//    {
//        $aPost = PostBuilder::aPost()->build();
//
//        $aPost->updateTitle(
//            $newTitle = new Title('A New Title')
//        );
//
//        $this->assertEquals($newTitle, $aPost->title());
//    }
//
//    public function testAPostBodyCanBeUpdated()
//    {
//        $aPost = PostBuilder::aPost()->build();
//
//        $aPost->updateBody(
//            $newBody = 'A New Body Of Post'
//        );
//
//        $this->assertEquals($newBody, $aPost->body());
//    }
//
//    public function testAPostHasAnAuthor()
//    {
//        $aPost = PostBuilder::aPost()
//            ->withUserId($userId = new UserId(1))
//            ->build();
//
//        $this->assertTrue($aPost->isAuthor($userId));
//        $this->assertFalse($aPost->isAuthor(new UserId(100)));
//    }
//
//    public function testAPostBelongsToBlog()
//    {
//        $aPost = PostBuilder::aPost()
//            ->withBlogId($blogId = new BlogId(1))
//            ->build();
//
//        $this->assertTrue($aPost->belongsTo($blogId));
//        $this->assertFalse($aPost->belongsTo(new BlogId(100)));
//    }
//
//    public function testOnCreateItShouldPublishPostCreatedEvent()
//    {
//        $subscriber = new SpySubscriber();
//        $id = DomainEventPublisher::instance()->subscribe($subscriber);
//
//        PostBuilder::aPost()
//            ->withPostId($postId = new PostId(1))
//            ->build();
//
//        DomainEventPublisher::instance()->unsubscribe($id);
//
//        $this->assertInstanceOf(PostCreated::class, $subscriber->domainEvent);
//        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
//    }
//
//    public function testOnPublishItShouldPublishPostPublishedEvent()
//    {
//        $subscriber = new SpySubscriber();
//
//        $aPost = PostBuilder::aPost()
//            ->withPostId($postId = new PostId(1))
//            ->build();
//
//        $id = DomainEventPublisher::instance()->subscribe($subscriber);
//
//        $aPost->publish();
//
//        DomainEventPublisher::instance()->unsubscribe($id);
//
//        $this->assertInstanceOf(PostPublished::class, $subscriber->domainEvent);
//        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
//    }
//
//    public function testOnDeleteItShouldPublishPostDeletedEvent()
//    {
//        $subscriber = new SpySubscriber();
//
//        $aPost = PostBuilder::aPost()
//            ->withPostId($postId = new PostId(1))
//            ->build();
//
//        $id = DomainEventPublisher::instance()->subscribe($subscriber);
//
//        $aPost->delete();
//
//        DomainEventPublisher::instance()->unsubscribe($id);
//
//        $this->assertInstanceOf(PostDeleted::class, $subscriber->domainEvent);
//        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
//    }
//
//    public function testOnUpdateTitleItShouldPublishPostTitleUpdateEvent()
//    {
//        $subscriber = new SpySubscriber();
//
//        $aPost = PostBuilder::aPost()
//            ->withPostId($postId = new PostId(1))
//            ->build();
//
//        $id = DomainEventPublisher::instance()->subscribe($subscriber);
//
//        $aPost->updateTitle($title = new Title('A New Post Title'));
//
//        DomainEventPublisher::instance()->unsubscribe($id);
//
//        $this->assertInstanceOf(PostTitleUpdated::class, $subscriber->domainEvent);
//        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
//        $this->assertEquals($subscriber->domainEvent->title(), $title);
//    }
//
//    public function testOnUpdateTitleItShouldPublishPostBodyUpdateEvent()
//    {
//        $subscriber = new SpySubscriber();
//
//        $aPost = PostBuilder::aPost()
//            ->withPostId($postId = new PostId(1))
//            ->build();
//
//        $id = DomainEventPublisher::instance()->subscribe($subscriber);
//
//        $aPost->updateBody($body = 'A New Post Body');
//
//        DomainEventPublisher::instance()->unsubscribe($id);
//
//        $this->assertInstanceOf(PostContentUpdated::class, $subscriber->domainEvent);
//        $this->assertTrue($subscriber->domainEvent->postId()->equals($postId));
//        $this->assertEquals($subscriber->domainEvent->body(), $body);
//    }
}