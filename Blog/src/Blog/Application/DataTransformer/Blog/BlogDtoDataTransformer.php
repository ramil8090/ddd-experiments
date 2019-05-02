<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 15:14
 */

namespace Blog\Application\DataTransformer\Blog;


use Blog\Domain\Model\Blog\Blog;

class BlogDtoDataTransformer implements BlogDataTransformer
{
    /**
     * @var Blog
     */
    private $blog;

    /**
     * @param Blog $blog
     */
    public function write(Blog $blog): void
    {
        $this->blog = $blog;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        return [
            'id' => $this->blog->blogId(),
            'title' => $this->blog->title(),
            'created_at' => $this->blog->creationDate()
        ];
    }
}