<?php

namespace Blog\Application\Service\Post;


class CreatePostRequest
{
    private $blogId;
    private $userId;
    private $title;
    private $body;

    public function __construct(
        $blogId,
        $userId,
        $title,
        $body
    )
    {
        $this->blogId = $blogId;
        $this->userId = $userId;
        $this->title = $title;
        $this->body = $body;
    }

    public function blogId()
    {
        return $this->blogId;
    }

    public function userId()
    {
        return $this->userId;
    }

    public function title()
    {
        return $this->title;
    }

    public function body()
    {
        return $this->body;
    }
}