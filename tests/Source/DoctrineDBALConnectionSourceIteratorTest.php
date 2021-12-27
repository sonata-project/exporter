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

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\DoctrineDBALConnectionSourceIterator;

final class DoctrineDBALConnectionSourceIteratorTest extends TestCase
{
    protected function setUp(): void
    {
        if (!\extension_loaded('pdo_sqlite') || !class_exists(Driver\PDO\SQLite\Driver::class)) {
            static::markTestSkipped('The sqlite extension is not available.');
        }
    }

    public function testRewindWithEmptyQuery(): void
    {
        $connection = DriverManager::getConnection(['url' => 'sqlite:///:memory:']);
        $driverConnection = $connection->getWrappedConnection();

        $iterator = new DoctrineDBALConnectionSourceIterator($driverConnection, '');
        $iterator->rewind();

        static::assertCount(0, iterator_to_array($iterator));
    }
}
