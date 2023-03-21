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

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\DoctrineODMQuerySourceIterator;
use Sonata\Exporter\Tests\Source\Fixtures\Document;

final class DoctrineODMQuerySourceIteratorTest extends TestCase
{
    private DocumentManager $dm;

    protected function setUp(): void
    {
        if (!\extension_loaded('mongodb')) {
            static::markTestSkipped('The mongodb extension is not available.');
        }

        $this->dm = DocumentManager::create(null, $this->createConfiguration());

        $documentA = new Document();
        $documentB = new Document();
        $documentC = new Document();

        $this->dm->persist($documentA);
        $this->dm->persist($documentB);
        $this->dm->persist($documentC);
        $this->dm->flush();
    }

    protected function tearDown(): void
    {
        $this->dm->createQueryBuilder(Document::class)
            ->remove()
            ->getQuery()
            ->execute();
    }

    public function testGetDateTimeFormat(): void
    {
        $query = $this->dm->createQueryBuilder(Document::class)->getQuery();

        $iterator = new DoctrineODMQuerySourceIterator($query, []);

        static::assertSame(\DateTimeInterface::ATOM, $iterator->getDateTimeFormat());
    }

    public function testHandler(): void
    {
        $query = $this->dm->createQueryBuilder(Document::class)->getQuery();

        $iterator = new DoctrineODMQuerySourceIterator($query, ['id']);

        static::assertCount(3, iterator_to_array($iterator));
    }

    /**
     * @psalm-suppress InternalMethod
     */
    public function testEntityManagerClear(): void
    {
        $query = $this->dm->createQueryBuilder(Document::class)->getQuery();

        $batchSize = 2;
        $iterator = new DoctrineODMQuerySourceIterator($query, ['id'], 'r', $batchSize);

        foreach ($iterator as $i => $item) {
            static::assertSame(0 === $i % $batchSize ? 0 : $i, $this->dm->getUnitOfWork()->size());
        }
    }

    private function createConfiguration(): Configuration
    {
        $config = new Configuration();

        $directory = sys_get_temp_dir().'/mongodb';

        $config->setProxyDir($directory);
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir($directory);
        $config->setHydratorNamespace('Hydrators');
        $config->setPersistentCollectionDir($directory);
        $config->setPersistentCollectionNamespace('PersistentCollections');
        $config->setMetadataDriverImpl(new AttributeDriver());

        return $config;
    }
}
