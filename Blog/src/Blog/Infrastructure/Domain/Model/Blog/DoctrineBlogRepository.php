<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 17:19
 */

namespace Blog\Infrastructure\Domain\Model\Blog;


use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Blog\BlogRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class DoctrineBlogRepository implements BlogRepository
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return BlogId
     */
    public function nextIdentity(): BlogId
    {
        return new BlogId(Uuid::uuid4());
    }

    /**
     * @param Blog $blog
     */
    public function add(Blog $blog): void
    {
        $this->em->persist($blog);
        $this->em->flush();
    }

    /**
     * @param Blog $blog
     */
    public function save(Blog $blog): void
    {
        $this->em->flush($blog);
    }

    /**
     * @param BlogId $blogId
     * @return Blog
     */
    public function blogOfId(BlogId $blogId): ?Blog
    {
        return $this->em->find('Blog\Domain\Model\Blog\Blog', $blogId);
    }
}