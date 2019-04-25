<?php

namespace Blog\Infrastructure\IdentityAccess;

use Blog\Domain\Model\Common\UserId;
use Blog\Domain\Model\Common\UserRole;

class HttpUserRole implements UserRole
{

    /**
     * Check user is moderator is IdentityAccess bounded context
     * @param UserId $userId
     * @return bool
     */
    public function isModerator(UserId $userId): bool
    {
        /**
         * @todo implementation
        */
        return false;
    }
}