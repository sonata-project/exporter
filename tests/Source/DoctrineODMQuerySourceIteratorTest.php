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

use DateTimeInterface;
use Doctrine\ODM\MongoDB\Query\Query;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\DoctrineODMQuerySourceIterator;

class DoctrineODMQuerySourceIteratorTest extends TestCase
{
    public function testGetDateTimeFormat(): void
    {
        $query = $this->createStub(Query::class);

        $iterator = new DoctrineODMQuerySourceIterator($query, []);

        self::assertSame(DateTimeInterface::ATOM, $iterator->getDateTimeFormat());
    }
}
