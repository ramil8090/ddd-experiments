<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 17:25
 */

namespace Blog\Domain\Model\Blog;


use Blog\Infrastructure\Domain\Model\Blog\DoctrineBlogRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

trait CreateDoctrineBlogRepositoryTrait
{
    /**
     * @return DoctrineBlogRepository
     */
    private function createBlogRepository()
    {
        $this->addCustomTypes();
        $em = $this->initEntityManager();
        $this->initSchema($em);

        return new DoctrineBlogRepository($em);
    }

    private function addCustomTypes()
    {
        if (!Type::hasType('BlogId')) {
            Type::addType('BlogId', '\Blog\Infrastructure\Domain\Model\Blog\DoctrineBlogIdType');
        }

        if (!Type::hasType('BlogTitle')) {
            Type::addType('BlogTitle', '\Blog\Infrastructure\Domain\Model\Blog\DoctrineTitleType');
        }

        if (!Type::hasType('BlogStatus')) {
            Type::addType('BlogStatus', '\Blog\Infrastructure\Domain\Model\Blog\DoctrineStatusType');
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
            $em->getClassMetadata('Blog\Domain\Model\Blog\Blog')
        ]);
    }
}