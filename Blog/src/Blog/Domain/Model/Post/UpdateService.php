<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 6/16/19
 * Time: 6:15 PM
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\Member\MemberService;

class UpdateService
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(PostRepository $postRepository, MemberService $memberService)
    {
        $this->postRepository = $postRepository;
        $this->memberService = $memberService;
    }

    public function updateTitle(PostId $postId, Title $title, string $username): Post
    {
        $post = $this->postRepository->postOfId($postId);

        if ($post == null) {
            throw new \DomainException("Post not found.");
        }

        $author = $this->memberService->authorFrom($post->blogId(), $username);
        $owner = $this->memberService->ownerFrom($post->blogId(), $username);
        $moderator = $this->memberService->moderatorFrom($username);

        if ($author !== null || $owner !== null || $moderator !== null) {
            $post->updateTitle($title);
            return $post;
        }

        throw new \DomainException("User not permitted to update post.");
    }

    public function updateContent(PostId $postId, string $content, string $username): Post
    {
        $post = $this->postRepository->postOfId($postId);

        if ($post == null) {
            throw new \DomainException("Post not found.");
        }

        $author = $this->memberService->authorFrom($post->blogId(), $username);
        $owner = $this->memberService->ownerFrom($post->blogId(), $username);
        $moderator = $this->memberService->moderatorFrom($username);

        if ($author !== null || $owner !== null || $moderator !== null) {
            $post->updateContent($content);
            return $post;
        }

        throw new \DomainException("User not permitted to update post.");
    }
}