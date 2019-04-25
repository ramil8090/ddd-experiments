<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 19:05
 */

namespace Blog\Domain\Model\Common;


interface UserRole
{
    /**
     * @param UserId $userId
     * @return bool
     */
    public function isModerator(UserId $userId): bool;
}