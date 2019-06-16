<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 07.06.19
 * Time: 16:03
 */

namespace Blog\Domain\Model\Member;


class Author extends Member
{
    public static function fromOwner(Owner $owner): Author
    {
        return new Author(
          $owner->username(),
          $owner->email(),
          $owner->fullName()
        );
    }
}