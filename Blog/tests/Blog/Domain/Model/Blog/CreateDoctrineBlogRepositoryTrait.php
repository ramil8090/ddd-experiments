<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 17:25
 */

namespace Blog\Domain\Model\Blog;


use Blog\Infrastructure\Persistence\Doctrine\Repository\DoctrineBlogRepository;
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
            Type::addType('BlogId', '\Blog\Infrastructure\Persistence\Doctrine\Type\Blog\DoctrineBlogIdType');
        }

        if (!Type::hasType('BlogTitle')) {
            Type::addType('BlogTitle', '\Blog\Infrastructure\Persistence\Doctrine\Type\Blog\DoctrineTitleType');
        }

        if (!Type::hasType('BlogStatus')) {
            Type::addType('BlogStatus', '\Blog\Infrastructure\Persistence\Doctrine\Type\Blog\DoctrineStatusType');
        }

        if (!Type::hasType('UserId')) {
            Type::addType('UserId', '\Blog\Infrastructure\Persistence\Doctrine\Type\User\DoctrineUserIdType');
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