<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 6/16/19
 * Time: 3:50 PM
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\Member\MemberService;

class ApproveService
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

    public function approve(PostId $postId, string $username): Post
    {
        $moderator = $this->memberService->moderatorFrom($username);

        if ($moderator == null) {
            throw new \DomainException("User is not moderator.");
        }

        $post = $this->postRepository->postOfId($postId);

        if ($post == null) {
            throw new \DomainException("Post not found.");
        }

        $post->approve();

        return $post;
    }
}