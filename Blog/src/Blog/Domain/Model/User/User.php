<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 13.05.19
 * Time: 11:23
 */

namespace Blog\Domain\Model\User;


class User
{
    /**
     * @var UserId
     */
    private $userId;

    public function __construct(
        UserId $userId
    )
    {
        $this->userId = $userId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}