<?php

namespace Blog\Application\Service\Post;


class DeletePostRequest
{
    private $postId;

    public function __construct(
        $postId
    )
    {
        $this->postId = $postId;
    }

    public function postId()
    {
        return $this->postId;
    }
}