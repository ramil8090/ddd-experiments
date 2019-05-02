<?php

namespace Blog\Application\Service\Blog;

use Blog\Application\DataTransformer\Blog\BlogDataTransformer;
use Blog\Application\DataTransformer\Blog\BlogDtoDataTransformer;
use Blog\Domain\DomainEventPublisher;
use Blog\Domain\Model\Blog\BlogCreated;
use Blog\Domain\Model\Blog\BlogRepository;
use Blog\Domain\Model\Blog\CreateDoctrineBlogRepositoryTrait;
use Blog\Domain\Model\Common\SpySubscriber;
use PHPUnit\Framework\TestCase;

class CreateBlogServiceTest extends TestCase
{
    use CreateDoctrineBlogRepositoryTrait;

    /**
     * @var BlogRepository
     */
    private $blogRepository;
    /**
     * @var BlogDataTransformer
     */
    private $blogDataTransformer;
    /**
     * @var CreateBlogService
     */
    private $createBlogService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blogRepository = $this->createBlogRepository();
        $this->blogDataTransformer = new BlogDtoDataTransformer();

        $this->createBlogService = new CreateBlogService(
            $this->blogRepository,
            $this->blogDataTransformer
        );
    }

    public function testAfterCreateItShouldBeInTheRepository()
    {
        $blogDto = $this->executeCreateBlogService();

        $blog = $this->blogRepository->blogOfId($blogDto['id']);

        $this->assertNotNull($blog);
    }

    public function testItShouldPublishBlogCreatedEvent()
    {
        $spySubscriber = new SpySubscriber();

        $id = DomainEventPublisher::instance()->subscribe($spySubscriber);

        $this->executeCreateBlogService();

        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertInstanceOf(BlogCreated::class, $spySubscriber->domainEvent);
    }

    private function executeCreateBlogService()
    {
        return $this->createBlogService->execute(new CreateBlogRequest(
            1,
           'A Blog Title'
        ));
    }


}