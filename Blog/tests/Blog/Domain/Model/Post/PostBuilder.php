<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 15.04.19
 * Time: 15:13
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Common\UserId;

class PostBuilder
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
     * @var Title
     */
    private $title;
    /**
     * @var string
     */
    private $body;
    /**
     * @var UserId
     */
    private $userId;

    private function __construct()
    {
        $this->postId = new PostId(1);
        $this->blogId = new BlogId(1);
        $this->body = 'A Post Body';
        $this->title = new Title('A Post Title');
        $this->userId = new UserId(1);
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

    public function withBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    public function withTitle(Title $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function withUserId(UserId $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function withBlogId(BlogId $blogId): self
    {
        $this->blogId = $blogId;

        return $this;
    }

    public function build(): Post
    {
        return new Post(
            $this->postId,
            $this->blogId,
            $this->userId,
            $this->title,
            $this->body
        );
    }
}