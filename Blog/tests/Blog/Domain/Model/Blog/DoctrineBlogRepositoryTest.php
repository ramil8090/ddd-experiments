<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 18:19
 */

namespace Blog\Domain\Model\Blog;


use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\User\UserId;
use Blog\Infrastructure\Domain\Model\Blog\DoctrineBlogRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class DoctrineBlogRepositoryTest extends TestCase
{
    use CreateDoctrineBlogRepositoryTrait;

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
}