<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 15:16
 */

namespace Blog\Domain\Model\Blog;


interface BlogRepository
{
    /**
     * @return BlogId
     */
    public function nextIdentity(): BlogId;

    /**
     * @param Blog $blog
     */
    public function add(Blog $blog): void;

    /**
     * @param Blog $blog
     */
    public function save(Blog $blog): void;

    /**
     * @param BlogId $blogId
     * @return Blog|null
     */
    public function blogOfId(BlogId $blogId): ?Blog;
}