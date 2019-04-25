<?php

namespace Blog\Infrastructure\Domain\Model\Common;

class DoctrineUserIdType extends DoctrineEntityId
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