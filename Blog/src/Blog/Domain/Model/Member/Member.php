<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 07.06.19
 * Time: 16:00
 */

namespace Blog\Domain\Model\Member;


class Member
{
    private $username;
    private $email;
    private $fullName;

    public function __construct(
        $username,
        $email,
        $fullName
    )
    {
        $this->username = $username;
        $this->email = $email;
        $this->fullName = $fullName;
    }

    public function username()
    {
        return $this->username;
    }

    public function email()
    {
        return $this->email;
    }

    public function fullName()
    {
        return $this->fullName;
    }

    public function equals(Member $member): bool
    {
        return $this->username() == $member->username()
            && $this->email() == $member->email()
            && $this->fullName == $member->fullName();
    }
}