<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 17:24
 */

namespace Blog\Domain\Model\Blog;



use Blog\Domain\Model\Member\Owner;
use Blog\Domain\Model\User\UserId;

class BlogBuilder
{
    private $blogId;
    private $owner;
    private $authors;
    private $title;

    private function __construct()
    {
        $this->blogId = new BlogId(1);
        $this->owner = new Owner(
            'username',
            'user@email.com',
            'John Doe'
        );
        $this->authors = [];
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

    public function withOwner(Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function withAuthors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function withTitle(Title $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function build(): Blog
    {
        $blog = new Blog(
            $this->blogId,
            $this->owner,
            $this->title
        );

        if ($this->authors) {
            array_map(function ($author) use ($blog) {
                $blog->appendAuthor($author);
            }, $this->authors);
        }

        return $blog;
    }
}