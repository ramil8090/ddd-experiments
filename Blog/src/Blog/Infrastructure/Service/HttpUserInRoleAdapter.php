<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 13.05.19
 * Time: 12:00
 */

namespace Blog\Infrastructure\Service;


use Blog\Domain\Model\User\UserId;
use GuzzleHttp\Client;

class HttpUserInRoleAdapter implements UserInRoleAdapter
{
    private static $HOST            = 'localhost';
    private static $PORT            = '8081';
    private static $PROTOCOL        = 'http';

    public function toUser(UserId $userId, $roleName, $userClassName)
    {
        try {
            $response = $this->makeRequest($userId, $roleName);

            if (204 === (int) $response->getStatusCode()) {
                return;
            }

            if (200 === (int) $response->getStatusCode()) {
                $user = (new RestApiUserTranslator())->toUserFromRepresentation(
                    $response->getBody()->getContents(),
                    $userClassName
                );
            } else {
                throw new \Exception(
                    'There was a problem requesting the user id: ' . $userId . ' in role: ' . $roleName . ' with resulting status: ' . $response->getStatus()
                );
            }

        } finally {
            return $user;
        }
    }

    private function makeRequest(UserId $userId, $roleName)
    {
        return (new Client())
            ->request('GET', $this->buildURLFor(), [
                'query' => [
                    'user' => $userId->getId(),
                    'inRole' => $roleName
                ]
            ]);
    }

    private function buildURLFor()
    {
        return static::$PROTOCOL . '://' . static::$HOST . ':' . static::$PORT;
    }
}