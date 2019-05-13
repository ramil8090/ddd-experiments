<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 17:23
 */

namespace Blog\Domain\Model\Post;


use Blog\Infrastructure\Persistence\Doctrine\Repository\DoctrinePostRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

trait CreateDoctrinePostRepositoryTrait
{
    /**
     * @return DoctrinePostRepository
     */
    public function createPostRepository()
    {
        $this->addCustomTypes();
        $em = $this->initEntityManager();
        $this->initSchema($em);

        return new DoctrinePostRepository($em);
    }

    private function addCustomTypes()
    {
        if (!Type::hasType('PostId')) {
            Type::addType('PostId', '\Blog\Infrastructure\Persistence\Doctrine\Type\Post\DoctrinePostIdType');
        }

        if (!Type::hasType('PostTitle')) {
            Type::addType('PostTitle', '\Blog\Infrastructure\Persistence\Doctrine\Type\Post\DoctrineTitleType');
        }

        if (!Type::hasType('PostStatus')) {
            Type::addType('PostStatus', '\Blog\Infrastructure\Persistence\Doctrine\Type\Post\DoctrineStatusType');
        }

        if (!Type::hasType('UserId')) {
            Type::addType('UserId', '\Blog\Infrastructure\Persistence\Doctrine\Type\User\DoctrineUserIdType');
        }

        if (!Type::hasType('BlogId')) {
            Type::addType('BlogId', '\Blog\Infrastructure\Persistence\Doctrine\Type\Blog\DoctrineBlogIdType');
        }
    }

    protected function initEntityManager()
    {
        return EntityManager::create(
            ['url' => 'sqlite:///:memory:'],
            Setup::createYAMLMetadataConfiguration(
                ['/app/src/Blog/Infrastructure/Persistence/Doctrine/Mapping'],
                $devMode = true
            )
        );
    }

    private function initSchema(EntityManager $em)
    {
        $tool = new SchemaTool($em);
        $tool->createSchema([
            $em->getClassMetadata('Blog\Domain\Model\Post\Post')
        ]);
    }
}