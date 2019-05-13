<?php

namespace Blog\Infrastructure\Persistence\Doctrine\Type\User;

use Blog\Infrastructure\Persistence\Doctrine\Type\Common\DoctrineEntityId;

class DoctrineUserIdType extends DoctrineEntityId
{
    public function getName()
    {
        return 'UserId';
    }

    protected function getNamespace()
    {
        return 'Blog\Domain\Model\User';
    }
}