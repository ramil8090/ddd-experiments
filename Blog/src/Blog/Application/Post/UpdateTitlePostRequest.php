<?php

namespace Blog\Application\Service\Post;


class UpdateTitlePostRequest
{
    private $postId;
    private $title;

    public function __construct(
        $postId,
        $title
    )
    {
        $this->postId = $postId;
        $this->title = $title;
    }

    public function postId()
    {
        return $this->postId;
    }

    public function title()
    {
        return $this->title;
    }
}