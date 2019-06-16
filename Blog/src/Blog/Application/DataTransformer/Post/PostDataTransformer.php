<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 15:19
 */

namespace Blog\Application\DataTransformer\Post;


use Blog\Domain\Model\Blog\Post\Post;

interface PostDataTransformer
{
    /**
     * @param Post $post
     */
    public function write(Post $post): void;

    /**
     * @return mixed
     */
    public function read();
}