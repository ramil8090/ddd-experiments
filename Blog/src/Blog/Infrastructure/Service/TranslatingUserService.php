<?php

namespace Blog\Infrastructure\Service;

use Blog\Domain\Model\User\Moderator;
use Blog\Domain\Model\User\UserId;
use Blog\Domain\Model\User\UserService;

class TranslatingUserService implements UserService
{
    private $userInRoleAdapter;

    public function __construct(UserInRoleAdapter $userInRoleAdapter)
    {
        $this->userInRoleAdapter = $userInRoleAdapter;
    }

    public function moderatorFrom(UserId $userId): ?Moderator
    {
        return $this->userInRoleAdapter->toUser(
            $userId.
            'Moderator',
            '\Blog\Domain\Model\User\Moderator'
        );;
    }
}