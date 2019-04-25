<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 14:59
 */

namespace Blog\Domain\Model\Post;


interface PostRepository
{
    /**
     * @return PostId
     */
    public function nextIdentity(): PostId;

    /**
     * @param Post $post
     */
    public function add(Post $post): void;

    /**
     * @param Post $post
     */
    public function save(Post $post): void;

    /**
     * @param PostId $postId
     * @return Post|null
     */
    public function postOfId(PostId $postId): ?Post;

    /**
     * @param \DateTimeImmutable $sinceADate
     * @return Post[]
     */
    public function latestPosts(\DateTimeImmutable $sinceADate): array;
}