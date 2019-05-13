<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 13.05.19
 * Time: 11:31
 */

namespace Blog\Infrastructure\Service;


interface UserTranslator
{
    /**
     * @param string $userInRoleRepresentation
     * @param string $userClassName
     * @return mixed
     */
    public function toUserFromRepresentation(string $userInRoleRepresentation, string $userClassName);
}