<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 16:27
 */

namespace Blog\Application\Service\Blog;


use Blog\Application\DataTransformer\BlogDataTransformer;
use Blog\Application\Service\ApplicationService;
use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Blog\BlogRepository;
use Blog\Domain\Model\Common\UserId;
use Blog\Domain\Model\Common\UserRole;

class DeleteBlogService implements ApplicationService
{
    /**
     * @var BlogRepository
     */
    private $blogRepository;
    /**
     * @var UserRole
     */
    private $userRoleAdapter;
    /**
     * @var BlogDataTransformer
     */
    private $blogDataTransformer;


    public function __construct(
        BlogRepository $blogRepository,
        UserRole $userRoleAdapter,
        BlogDataTransformer $blogDataTransformer
    )
    {
        $this->blogRepository = $blogRepository;
        $this->userRoleAdapter = $userRoleAdapter;
        $this->blogDataTransformer = $blogDataTransformer;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $userId = new UserId($request->userId());
        $blogId = new BlogId($request->blogId());

        $blog = $this->blogRepository->blogOfId($blogId);

        if (!$this->assertUserHasPermissions($blog, $userId)) {
            throw new \DomainException('User not permitted to delete blog.');
        }

        $blog->delete();
        $this->blogRepository->save($blog);

        $this->blogDataTransformer->write($blog);

        return $this->blogDataTransformer->read();
    }

    private function assertUserHasPermissions(Blog $blog, UserId $userId): bool
    {
        return $blog->isOwnedBy($userId) || $this->userRoleAdapter->isModerator($userId);
    }
}