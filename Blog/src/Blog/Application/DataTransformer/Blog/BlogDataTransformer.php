<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 15:01
 */

namespace Blog\Application\DataTransformer;


use Blog\Domain\Model\Blog\Blog;

interface BlogDataTransformer
{
    /**
     * @param Blog $blog
     */
    public function write(Blog $blog): void;

    /**
     * @return mixed
     */
    public function read();
}