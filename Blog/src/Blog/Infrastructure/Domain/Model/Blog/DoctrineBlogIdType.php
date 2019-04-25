<?php

namespace Blog\Infrastructure\Domain\Model\Blog;

use Blog\Infrastructure\Domain\Model\Common\DoctrineEntityId;

class DoctrineBlogIdType extends DoctrineEntityId
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