<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 24.04.19
 * Time: 17:02
 */

namespace Blog\Infrastructure\Persistence\Doctrine\Repository;


use Blog\Domain\Model\Blog\Post\Post;
use Blog\Domain\Model\Blog\Post\PostId;
use Blog\Domain\Model\Blog\Post\PostRepository;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;

class DoctrinePostRepository implements PostRepository
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
     * @return PostId
     */
    public function nextIdentity(): PostId
    {
        return new PostId(Uuid::uuid4());
    }

    /**
     * @param Post $post
     */
    public function add(Post $post): void
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * @param Post $post
     */
    public function save(Post $post): void
    {
        $this->em->flush($post);
    }

    /**
     * @param PostId $postId
     * @return Post
     */
    public function postOfId(PostId $postId): ?Post
    {
        return $this->em->find('Blog\Domain\Model\Post\Post', $postId);
    }

    /**
     * @param \DateTimeImmutable $sinceADate
     * @return Post[]
     */
    public function latestPosts(\DateTimeImmutable $sinceADate): array
    {
        return $this->em->createQueryBuilder()
            ->select('p')
            ->from('Blog\Domain\Model\Post\Post', 'p')
            ->where('p.createdAt > :since')
            ->setParameter(':since', $sinceADate)
            ->getQuery()
            ->getResult();
    }
}