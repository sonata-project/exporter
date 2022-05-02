<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Tests\Source;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\DoctrineORMQuerySourceIterator;
use Sonata\Exporter\Tests\Source\Fixtures\Entity;

final class DoctrineORMQuerySourceIteratorTest extends TestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp(): void
    {
        if (!\extension_loaded('pdo_sqlite') || !class_exists(Driver\PDO\SQLite\Driver::class)) {
            static::markTestSkipped('The sqlite extension is not available.');
        }

        $this->em = EntityManager::create($this->createConnection(), $this->createConfiguration());

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema([
            $this->em->getClassMetadata(Entity::class),
        ]);

        $entityA = new Entity();
        $entityB = new Entity();
        $entityC = new Entity();

        $this->em->persist($entityA);
        $this->em->persist($entityB);
        $this->em->persist($entityC);
        $this->em->flush();
    }

    protected function tearDown(): void
    {
        $this->em
            ->createQuery('DELETE FROM '.Entity::class)
            ->execute();
    }

    public function testEntityManagerClear(): void
    {
        $query = $this->em
            ->getRepository(Entity::class)
            ->createQueryBuilder('e')
            ->getQuery();

        $batchSize = 2;
        $iterator = new DoctrineORMQuerySourceIterator($query, ['id'], 'r', $batchSize);

        foreach ($iterator as $i => $item) {
            static::assertSame(0 === $i % $batchSize ? 0 : $i, $this->em->getUnitOfWork()->size());
        }
    }

    /**
     * @psalm-suppress InternalMethod
     */
    private function createConnection(): Connection
    {
        return new Connection([], new Driver\PDO\SQLite\Driver());
    }

    private function createConfiguration(): Configuration
    {
        $config = new Configuration();

        $directory = sys_get_temp_dir().'/sqlite';

        $config->setProxyDir($directory);
        $config->setProxyNamespace('Proxies');
        $config->setMetadataDriverImpl($this->createMetadataDriverImplementation());

        return $config;
    }

    private function createMetadataDriverImplementation(): MappingDriver
    {
        return new AnnotationDriver(new AnnotationReader());
    }
}
