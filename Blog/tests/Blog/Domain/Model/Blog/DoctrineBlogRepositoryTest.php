<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 18:19
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Common\UserId;
use Blog\Infrastructure\Domain\Model\Post\DoctrinePostRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class DoctrinePostRepositoryTest extends TestCase
{
    /**
     * @var DoctrinePostRepository
     */
    private $postRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postRepository = $this->createPostRepository();
    }

    public function testItShouldRemovePost()
    {
        $post = $this->persistPost(
            $blogId = new BlogId(1),
            $userId = new UserId(1),
            $title = new Title('A Post Title'),
            $body = 'A Post Body'
        );

        $this->postRepository->remove($post);

        $this->assertPostExist($post->postId());
    }

    private function assertPostExist($id)
    {
        $result = $this->postRepository->postOfId($id);
        $this->assertNull($result);
    }

    private function persistPost(BlogId $blogId, UserId $userId, Title $title, string $body)
    {
        $this->postRepository->add(
            $post = new Post(
                $this->postRepository->nextIdentity(),
                $blogId,
                $userId,
                $title,
                $body
            )
        );

        return $post;
    }

    #################### Init repository ####################
    /**
     * @return DoctrinePostRepository
     */
    private function createPostRepository()
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