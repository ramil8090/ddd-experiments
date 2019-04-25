<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 18:21
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\Model\Common\UserId;

/**
 * Interface DeleteBlog
 * Delete blog if user has permissions
 * @package Blog\Domain\Model\Blog
 */
interface DeleteBlog
{
    /**
     * @param BlogId $blogId
     * @param UserId $userId
     * @return mixed
     */
    public function execute(BlogId $blogId, UserId $userId);
}