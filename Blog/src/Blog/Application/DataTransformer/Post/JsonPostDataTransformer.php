<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 15:21
 */

namespace Blog\Application\DataTransformer\Post;


use Blog\Domain\Model\Blog\Post\Post;

class JsonPostDataTransformer implements PostDataTransformer
{
    /**
     * @var Post
     */
    private $post;
    /**
     * @param Post $post
     */
    public function write(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        return json_encode([
            'id' => $this->post->postId(),
            'title' => $this->post->title(),
            'body' => $this->post->body(),
            'created_at' => $this->post->creationDate(),
            'published_at' => $this->post->publicationDate()
        ]);
    }
}