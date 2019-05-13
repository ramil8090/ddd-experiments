<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 07.05.19
 * Time: 12:40
 */

namespace Blog\Infrastructure\Service;


use Blog\Domain\Model\User\UserId;

interface UserInRoleAdapter
{
    public function toUser(UserId $userId, $roleName, $userClassName);
}