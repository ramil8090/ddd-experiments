<?php

namespace Blog\Infrastructure\Persistence\Doctrine\Type\Blog;



use Blog\Infrastructure\Persistence\Doctrine\Type\Common\DoctrineEntityId;

class DoctrineBlogIdType extends DoctrineEntityId
{
    public function getName()
    {
        return 'BlogId';
    }

    protected function getNamespace()
    {
        return 'Blog\Domain\Model\Blog';
    }
}