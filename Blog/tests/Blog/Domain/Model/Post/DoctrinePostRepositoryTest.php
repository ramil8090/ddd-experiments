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
    use CreateDoctrinePostRepositoryTrait;

    /**
     * @var DoctrinePostRepository
     */
    private $postRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postRepository = $this->createPostRepository();
    }

    public function testItShouldLogicallyDeletePost()
    {
        $post = $this->persistPost(
            $blogId = new BlogId(1),
            $userId = new UserId(1),
            $title = new Title('A Post Title'),
            $body = 'A Post Body'
        );

        $post->delete();

        $this->postRepository->save($post);

        $this->assertPostExist($post->postId());
        $this->assertPostIsLogicallyDeleted($post->postId());
    }

    private function assertPostExist($id)
    {
        $result = $this->postRepository->postOfId($id);
        $this->assertNotNull($result);
    }

    private function assertPostIsLogicallyDeleted($id)
    {
        $result = $this->postRepository->postOfId($id);
        $this->assertTrue($result->isDeleted());
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
}