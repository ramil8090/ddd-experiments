<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 6/16/19
 * Time: 5:39 PM
 */

namespace Blog\Domain\Model\Post;


use Blog\Domain\Model\Member\MemberService;

class RejectService
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

    public function reject(PostId $postId, string $username): Post
    {
        $moderator = $this->memberService->moderatorFrom($username);

        if ($moderator == null) {
            throw new \DomainException("User is not moderator.");
        }

        $post = $this->postRepository->postOfId($postId);

        if ($post == null) {
            throw new \DomainException("Post not found.");
        }

        $post->reject();

        return $post;
    }
}