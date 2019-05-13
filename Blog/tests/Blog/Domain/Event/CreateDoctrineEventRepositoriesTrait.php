<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 01.05.19
 * Time: 17:25
 */

namespace Blog\Domain\Event;


use Blog\Infrastructure\Persistence\Doctrine\Repository\Notification\DoctrineEventStore;
use Blog\Infrastructure\Persistence\Doctrine\Repository\Notification\DoctrinePublishedMessageTracker;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

trait CreateDoctrineEventRepositoriesTrait
{
    /**
     * @return DoctrineEventStore
     */
    private function createStoredEventRepository()
    {
        $em = $this->initEntityManager();
        $this->initStoredEventSchema($em);

        return new DoctrineEventStore($em);
    }

    /**
     * @return DoctrinePublishedMessageTracker
     */
    private function createPublishedMessageRepository()
    {
        $em = $this->initEntityManager();
        $this->initPublishedMessageSchema($em);

        return new DoctrinePublishedMessageTracker($em);
    }

    protected function initEntityManager()
    {
        return EntityManager::create(
            ['url' => 'sqlite:///:memory:'],
            Setup::createYAMLMetadataConfiguration(
                ['/app/src/Blog/Infrastructure/Persistence/Doctrine/Mapping/Notification'],
                $devMode = true
            )
        );
    }

    private function initStoredEventSchema(EntityManager $em)
    {
        $tool = new SchemaTool($em);
        $tool->createSchema([
            $em->getClassMetadata('Blog\Domain\Event\StoredEvent')
        ]);
    }

    private function initPublishedMessageSchema(EntityManager $em)
    {
        $tool = new SchemaTool($em);
        $tool->createSchema([
            $em->getClassMetadata('Blog\Domain\Event\PublishedMessage')
        ]);
    }
}