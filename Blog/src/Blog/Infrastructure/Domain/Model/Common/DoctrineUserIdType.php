<?php

namespace Blog\Infrastructure\Domain\Model\Common;

class UserIdType extends DoctrineEntityId
{
    public function getName()
    {
        return 'UserId';
    }

    protected function getNamespace()
    {
        return 'Blog\Domain\Model\Common';
    }
}