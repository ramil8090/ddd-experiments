<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 17:45
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\Model\Common\SpySubscriber;
use Blog\Domain\Model\Member\Author;
use Blog\Domain\Model\Member\Owner;
use Blog\Domain\Model\Post\PostId;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    public function testANewBlogIsActiveByDefault()
    {
        $aBlog = new Blog(
            new BlogId(1),
            new Owner(
                'username',
                'user@email.com',
                'John Doe'
            ),
            new Title('A Blog Title')
        );

        $this->assertTrue($aBlog->isActive());
    }

    public function testOnCreateItShouldPublishBlogCreatedEvent()
    {
        $blog = BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        $events = $blog->releaseEvents();
        $lastEvent = end($events);
        $this->assertInstanceOf(BlogCreated::class, $lastEvent);
        $this->assertTrue($lastEvent->blogId()->equals($blogId));
    }

    public function testABlogCanBeArchived()
    {
        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->archive();

        $this->assertTrue($aBlog->isArchived());
    }

    public function testOnDeleteItShouldPublishBlogDeletedEvent()
    {
        $blog = BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        $blog->archive();

        $events = $blog->releaseEvents();
        $lastEvent = end($events);

        $this->assertInstanceOf(BlogArchived::class, $lastEvent);
        $this->assertTrue($lastEvent->blogId()->equals($blogId));
        $this->assertTrue($blog->isArchived());
    }

    public function testErrorArchiveABlogWhenIsAlreadyArchived()
    {
        $this->expectExceptionMessage('A blog is already archived.');

        $blog = BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        $blog->archive();

        $this->assertTrue($blog->isArchived());

        $blog->archive();
    }

    public function testABlogCanBeRestored()
    {
        $subscriber = new SpySubscriber();

        $aBlog = BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        $aBlog->archive();

        $aBlog->restore();

        $events = $aBlog->releaseEvents();
        $lastEvent = end($events);

        $this->assertInstanceOf(BlogRestored::class, $lastEvent);
        $this->assertTrue($lastEvent->blogId()->equals($blogId));
        $this->assertTrue($aBlog->isActive());
    }

    public function testErrorRestoreBlogWhenIsAlreadyActive()
    {
        $this->expectExceptionMessage('A blog is already active.');

        $blog = BlogBuilder::aBlog()
            ->withBlogId($blogId = new BlogId(1))
            ->build();

        $blog->restore();
    }

    public function testABlogHasOwner()
    {
        $aBlog = BlogBuilder::aBlog()
            ->withOwner($owner = new Owner(
                $username = 'username',
                $email = 'user@email.com',
                $fullName = 'John Doe'
            ))
            ->build();

        $this->assertEquals($username, $aBlog->owner()->username());
        $this->assertEquals($email, $aBlog->owner()->email());
        $this->assertEquals($fullName, $aBlog->owner()->fullName());
    }

    public function testABlogCanAppendAuthor()
    {
        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->appendAuthor(new Author(
            $username = 'username',
            $email = 'user@email.com',
            $fullName = 'John Doe'
        ));

        $this->assertCount(1, $aBlog->authors());

        $events = $aBlog->releaseEvents();
        $lastEvent = end($events);

        $this->assertInstanceOf(BlogAuthorAppended::class, $lastEvent);
        $this->assertTrue($aBlog->blogId()->equals($lastEvent->blogId()));
        $this->assertEquals($username, $lastEvent->author()->username());
        $this->assertEquals($email, $lastEvent->author()->email());
        $this->assertEquals($fullName, $lastEvent->author()->fullName());
    }

    public function testErrorAppendAuthorIfIsAlreadyExist()
    {
        $this->expectExceptionMessage('An author is already exists.');

        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->appendAuthor(new Author(
            $username = 'username',
            $email = 'user@email.com',
            $fullName = 'John Doe'
        ));

        $aBlog->appendAuthor(new Author(
            $username = 'username',
            $email = 'user@email.com',
            $fullName = 'John Doe'
        ));
    }

    public function testABlogCanDetachAuthor()
    {
        $authors = [];
        $authors[] = new Author(
                $username = 'username',
                $email = 'user@email.com',
                $fullName = 'John Doe'
            );

        $aBlog = BlogBuilder::aBlog()
            ->withAuthors($authors)
            ->build();

        $this->assertCount(1, $aBlog->authors());

        $aBlog->detachAuthor(new Author(
            $username = 'username',
            $email = 'user@email.com',
            $fullName = 'John Doe'
        ));

        $this->assertCount(0, $aBlog->authors());

        $events = $aBlog->releaseEvents();
        $lastEvent = end($events);

        $this->assertInstanceOf(BlogAuthorDetached::class, $lastEvent);
        $this->assertTrue($aBlog->blogId()->equals($lastEvent->blogId()));
        $this->assertEquals($username, $lastEvent->author()->username());
        $this->assertEquals($email, $lastEvent->author()->email());
        $this->assertEquals($fullName, $lastEvent->author()->fullName());
    }

    public function testABlogCanCreatePost()
    {
        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->appendAuthor(
            $author = new Author(
                $username = 'username',
                $email = 'user@email.com',
                $fullName = 'John Doe'
            )
        );

        $aPost = $aBlog->createPost(
            $postId = new PostId(1),
            $title = new \Blog\Domain\Model\Post\Title('A Post Title'),
            $content = 'A Post Content',
            $author
        );

        $this->assertTrue($aPost->postId()->equals($postId));
        $this->assertEquals($aPost->title(), $title);
        $this->assertEquals($aPost->content(), $content);
        $this->assertTrue($aPost->author()->equal($author));
    }

    public function testErrorCreatePostWhenAuthorNotFound()
    {
        $this->expectExceptionMessage('An author not found.');

        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->createPost(
            new PostId(1),
            new \Blog\Domain\Model\Post\Title('A Post Title'),
            'A Post Content',
            new Author(
                $username = 'username',
                $email = 'user@email.com',
                $fullName = 'John Doe'
            )
        );
    }

    public function testErrorCreatePostIfBlogIsArchived()
    {
        $this->expectExceptionMessage("Error create post. A blog is archived.");

        $aBlog = BlogBuilder::aBlog()->build();

        $aBlog->archive();

        $aBlog->createPost(
            new PostId(1),
            new \Blog\Domain\Model\Post\Title('A Post Title'),
            'A Post Content',
            new Author(
                $username = 'username',
                $email = 'user@email.com',
                $fullName = 'John Doe'
            )
        );
    }
}