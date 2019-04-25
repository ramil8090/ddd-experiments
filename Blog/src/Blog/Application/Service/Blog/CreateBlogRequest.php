<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 16:02
 */

namespace Blog\Application\Service\Blog;


class CreateBlogRequest
{
    private $userId;
    private $title;

    public function __construct($userId, $title)
    {
        $this->userId = $userId;
        $this->title = $title;
    }

    public function title()
    {
        return $this->title;
    }

    public function userId()
    {
        return $this->userId;
    }
}