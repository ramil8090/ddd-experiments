<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 17:24
 */

namespace Blog\Domain\Model\Blog;



use Blog\Domain\Model\Common\UserId;

class BlogBuilder
{
    private $blogId;
    private $userId;
    private $title;

    private function __construct()
    {
        $this->blogId = new BlogId(1);
        $this->userId = new UserId(1);
        $this->title = new Title('A New Blog Title');
    }

    public static function aBlog(): self
    {
        return new self();
    }

    public function withBlogId(BlogId $blogId): self
    {
        $this->blogId = $blogId;

        return $this;
    }

    public function withUserId(UserId $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function withTitle(Title $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function build()
    {
        return new Blog(
            $this->blogId,
            $this->userId,
            $this->title
        );
    }
}