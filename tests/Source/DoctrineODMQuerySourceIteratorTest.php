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

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Query;
use MongoDB\Collection;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\DoctrineODMQuerySourceIterator;
use Sonata\Exporter\Tests\Source\Fixtures\ObjectWithToString;

class DoctrineODMQuerySourceIteratorTest extends TestCase
{
    public function testGetDateTimeFormat(): void
    {
        $documentManager = $this->createStub(DocumentManager::class);
        $classMetadata = new ClassMetadata(ObjectWithToString::class);
        $collection = $this->createStub(Collection::class);

        $query = new Query($documentManager, $classMetadata, $collection, ['type' => Query::TYPE_FIND]);

        $iterator = new DoctrineODMQuerySourceIterator($query, []);

        self::assertSame(\DateTimeInterface::ATOM, $iterator->getDateTimeFormat());
    }
}
