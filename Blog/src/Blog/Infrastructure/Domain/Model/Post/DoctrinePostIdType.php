<?php

namespace Blog\Infrastructure\Domain\Model\Post;

use Blog\Infrastructure\Domain\Model\Common\DoctrineEntityId;

class PostIdType extends DoctrineEntityId
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