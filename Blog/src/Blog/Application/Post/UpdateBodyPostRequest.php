<?php

namespace Blog\Application\Service\Post;


class UpdateBodyPostRequest
{
    private $postId;
    private $body;

    public function __construct(
        $postId,
        $body
    )
    {
        $this->postId = $postId;
        $this->body = $body;
    }

    public function postId()
    {
        return $this->postId;
    }

    public function body()
    {
        return $this->body;
    }
}