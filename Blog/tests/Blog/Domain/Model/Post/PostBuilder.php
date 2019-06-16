<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 15.04.19
 * Time: 15:13
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Member\Author;
use Blog\Domain\Model\Member\Owner;
use Blog\Domain\Model\User\UserId;

class PostBuilder
{
    /**
     * @var PostId
     */
    private $postId;
    /**
     * @var Blog
     */
    private $blog;
    /**
     * @var Title
     */
    private $title;
    /**
     * @var string
     */
    private $content;
    /**
     * @var Author
     */
    private $author;
    /**
     * @var Owner
     */
    private $owner;

    private function __construct()
    {
        $this->postId = new PostId(1);
        $this->blog = new Blog(
            new BlogId(1),
            new Owner(
                'username',
                'user@email.com',
                'John Doe'
            ),
            new \Blog\Domain\Model\Blog\Title('Blog Title')
        );
        $this->content = 'A Post Content';
        $this->title = new Title('A Post Title');
        $this->author = new Author(
            'username',
            'user@email.com',
            'John Doe'
        );
        $this->owner = new Owner(
            'username',
            'user@email.com',
            'John Doe'
        );
    }

    public static function aPost(): self
    {
        return new self();
    }

    public function withPostId(PostId $postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function withContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function withTitle(Title $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function withAuthor(Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function withOwner(Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function withBlog(Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function build(): Post
    {
        return Post::create(
            $this->postId,
            $this->blog,
            $this->owner,
            $this->author,
            $this->title,
            $this->content
        );
    }
}