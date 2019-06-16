<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 16:25
 */

namespace Blog\Application\Service\Blog;


class DeleteBlogRequest
{
    private $userId;
    private $blogId;

    public function __construct($blogId, $userId)
    {
        $this->blogId = $blogId;
        $this->userId = $userId;
    }

    public function blogId()
    {
        return $this->blogId;
    }

    public function userId()
    {
        return $this->userId;
    }
}