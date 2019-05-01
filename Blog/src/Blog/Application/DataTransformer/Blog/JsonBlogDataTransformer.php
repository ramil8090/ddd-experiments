<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 15:18
 */

namespace Blog\Application\DataTransformer;


use Blog\Domain\Model\Blog\Blog;

class JsonBlogDataTransformer implements BlogDataTransformer
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
        return json_encode([
            'id' => $this->blog->blogId(),
            'title' => $this->blog->title(),
            'created_at' => $this->blog->creationDate()
        ]);
    }
}