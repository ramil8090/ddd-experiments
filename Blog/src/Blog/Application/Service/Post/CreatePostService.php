<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 26.04.19
 * Time: 11:49
 */

namespace Blog\Application\Service\Post;


use Blog\Application\DataTransformer\Post\PostDataTransformer;
use Blog\Application\Service\ApplicationService;
use Blog\Domain\Model\Blog\BlogId;
use Blog\Domain\Model\Blog\BlogRepository;
use Blog\Domain\Model\Post\Post;
use Blog\Domain\Model\Post\PostRepository;
use Blog\Domain\Model\Post\Title;

class CreatePostService implements ApplicationService
{

    /**
     * @var BlogRepository
     */
    private $blogRepository;
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var PostDataTransformer
     */
    private $postDataTransformer;

    public function __construct(
        BlogRepository $blogRepository,
        PostRepository $postRepository,
        PostDataTransformer $postDataTransformer
    )
    {
        $this->blogRepository = $blogRepository;
        $this->postRepository = $postRepository;
        $this->postDataTransformer = $postDataTransformer;
    }

    /**
     * @param CreatePostRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $blog = $this->blogRepository->blogOfId(new BlogId($request->blogId()));

        if ($blog == null) {
            throw new \DomainException("Blog not found");
        }

        $post = $blog->createPost(
            $this->postRepository->nextIdentity(),
            new Title($request->title()),
            $request->body()
        );

        $this->postRepository->add($post);

        $this->postDataTransformer->write($post);

        return $this->postDataTransformer->read();
    }
}