<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 17:23
 */

namespace Blog\Domain\Model\Post;


use Blog\Infrastructure\Domain\Model\Post\DoctrinePostRepository;
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
            Type::addType('PostId', '\Blog\Infrastructure\Domain\Model\Post\DoctrinePostIdType');
        }

        if (!Type::hasType('PostTitle')) {
            Type::addType('PostTitle', '\Blog\Infrastructure\Domain\Model\Post\DoctrineTitleType');
        }

        if (!Type::hasType('PostStatus')) {
            Type::addType('PostStatus', '\Blog\Infrastructure\Domain\Model\Post\DoctrineStatusType');
        }

        if (!Type::hasType('UserId')) {
            Type::addType('UserId', '\Blog\Infrastructure\Domain\Model\Common\DoctrineUserIdType');
        }

        if (!Type::hasType('BlogId')) {
            Type::addType('BlogId', '\Blog\Infrastructure\Domain\Model\Blog\DoctrineBlogIdType');
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