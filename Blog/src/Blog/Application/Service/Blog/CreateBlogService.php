<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 25.04.19
 * Time: 16:14
 */

namespace Blog\Application\Service\Blog;


use Blog\Application\Service\ApplicationService;
use Blog\Domain\Model\Blog\Blog;
use Blog\Domain\Model\Blog\BlogRepository;
use Blog\Domain\Model\Blog\Title;
use Blog\Domain\Model\Common\UserId;

class CreateBlogService implements ApplicationService
{
    /**
     * @var BlogRepository
     */
    private $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $userId = new UserId($request->userId());
        $title = new Title($request->title());

        $blog = new Blog(
            $this->blogRepository->nextIdentity(),
            $userId,
            $title
        );

        $this->blogRepository->add($blog);
    }
}