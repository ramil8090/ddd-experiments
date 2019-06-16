<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 19:05
 */

namespace Blog\Domain\Model\Member;


use Blog\Domain\Model\Blog\BlogId;

interface MemberService
{
    /**
     * @param string $username
     * @return Moderator|null
     */
    public function moderatorFrom(string $username): ?Moderator;

    /**
     * @param BlogId $blogId
     * @param string $username
     * @return Owner|null
     */
    public function ownerFrom(BlogId $blogId, string $username): ?Owner;

    /**
     * @param BlogId $blogId
     * @param string $username
     * @return Author|null
     */
    public function authorFrom(BlogId $blogId, string $username): ?Author;
}