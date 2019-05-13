<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 13.05.19
 * Time: 11:35
 */

namespace Blog\Infrastructure\Service;


use ReflectionClass;

class RestApiUserTranslator implements UserTranslator
{

    /**
     * @param string $userInRoleRepresentation
     * @param string $userClassName
     * @return mixed
     */
    public function toUserFromRepresentation(string $userInRoleRepresentation, string $userClassName)
    {
        $dataArray = json_decode($userInRoleRepresentation, true);

        $userId = $dataArray['userId'];

        return $this->newUser($userId, $userClassName);
    }

    private function newUser($userId, string $userClassName)
    {
        $aReflectedCollaboratorClass = new ReflectionClass($userClassName);

        return $aReflectedCollaboratorClass->newInstance(
            $userId
        );
    }
}