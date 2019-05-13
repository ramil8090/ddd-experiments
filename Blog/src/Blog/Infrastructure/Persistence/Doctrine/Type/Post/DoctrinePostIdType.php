<?php

namespace Blog\Infrastructure\Persistence\Doctrine\Type\Post;



use Blog\Infrastructure\Persistence\Doctrine\Type\Common\DoctrineEntityId;

class DoctrinePostIdType extends DoctrineEntityId
{
    public function getName()
    {
        return 'PostId';
    }

    protected function getNamespace()
    {
        return 'Blog\Domain\Model\Post';
    }
}