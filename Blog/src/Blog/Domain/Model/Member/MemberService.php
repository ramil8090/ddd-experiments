<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 19:05
 */

namespace Blog\Domain\Model\Member;


interface MemberService
{
    /**
     * @param UserId $userId
     * @return Moderator|null
     */
    public function moderatorFrom(UserId $userId): ?Moderator;
}