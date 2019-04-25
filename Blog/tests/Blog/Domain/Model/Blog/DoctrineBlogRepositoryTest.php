<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 18:19
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Common\UserId;
use Blog\Infrastructure\Domain\Model\Blog\DoctrineBlogRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class DoctrineBlogRepositoryTest extends TestCase
{
    /**
     * @var DoctrineBlogRepository
     */
    private $blogRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blogRepository = $this->createBlogRepository();
    }

    public function testItShouldLogicallyDeleteBlog()
    {
        $blog = $this->persistBlog(
            $userId = new UserId(1),
            $title = new Title('A Blog Title')
        );

        $blog->delete();

        $this->blogRepository->save($blog);

        $this->assertBlogExist($blog->blogId());
        $this->assertBlogIsLogicallyDeleted($blog->blogId());
    }

    private function assertBlogExist($id)
    {
        $result = $this->blogRepository->blogOfId($id);
        $this->assertNotNull($result);
    }

    private function assertBlogIsLogicallyDeleted($id)
    {
        $result = $this->blogRepository->blogOfId($id);
        $this->assertTrue($result->isDeleted());
    }

    private function persistBlog(UserId $userId, Title $title)
    {
        $this->blogRepository->add(
            $blog = new Blog(
                $this->blogRepository->nextIdentity(),
                $userId,
                $title
            )
        );

        return $blog;
    }

    #################### Init repository ####################
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