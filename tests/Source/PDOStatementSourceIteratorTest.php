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

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\PDOStatementSourceIterator;

final class PDOStatementSourceIteratorTest extends TestCase
{
    private \PDO $dbh;

    private string $pathToDb;

    protected function setUp(): void
    {
        $this->pathToDb = tempnam(sys_get_temp_dir(), 'Sonata_exporter_');

        if (!\in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
            static::markTestSkipped('the sqlite extension is not available');
        }

        if (is_file($this->pathToDb)) {
            unlink($this->pathToDb);
        }

        $this->dbh = new \PDO('sqlite:'.$this->pathToDb);
        $this->dbh->exec('CREATE TABLE `user` (`id` int(11), `username` varchar(255) NOT NULL, `email` varchar(255) NOT NULL )');

        $data = [
            [1, 'john', 'john@foo.bar'],
            [2, 'john 2', 'john@foo.bar'],
            [3, 'john 3', 'john@foo.bar'],
        ];

        foreach ($data as $user) {
            $query = $this->dbh->prepare('INSERT INTO user (id, username, email) VALUES(?, ?, ?)');

            $query->execute($user);
        }
    }

    protected function tearDown(): void
    {
        unset($this->dbh);

        if (is_file($this->pathToDb)) {
            unlink($this->pathToDb);
        }
    }

    public function testHandler(): void
    {
        $stm = $this->dbh->prepare('SELECT id, username, email FROM user');
        $stm->execute();

        $iterator = new PDOStatementSourceIterator($stm);

        $data = [];
        foreach ($iterator as $user) {
            $data[] = $user;
        }

        static::assertCount(3, $data);
    }
}
