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
use Blog\Domain\Model\Post\PostId;
use Blog\Domain\Model\Post\PostRepository;
use Blog\Domain\Model\Post\Title;

class UpdateTitlePostService implements ApplicationService
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostDataTransformer
     */
    private $postDataTransformer;

    public function __construct(
        PostRepository $postRepository,
        PostDataTransformer $postDataTransformer
    )
    {
        $this->postRepository = $postRepository;
        $this->postDataTransformer = $postDataTransformer;
    }

    /**
     * @param UpdateTitlePostRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $post = $this->postRepository->postOfId(new PostId($request->postId()));

        if ($post == null) {
            throw new \DomainException("Post not found");
        }

        $post->updateTitle(new Title($request->title()));

        $this->postRepository->save($post);

        $this->postDataTransformer->write($post);

        return $this->postDataTransformer->read();
    }
}