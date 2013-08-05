<?php

namespace Exporter\Test\Source;

use Exporter\Source\PDOStatementSourceIterator;

class PDOStatementSourceIteratorTest extends \PHPUnit_Framework_TestCase
{

    protected $dbh;

    public function setUp()
    {
        if (!in_array('sqlite', \PDO::getAvailableDrivers())) {
            $this->markTestSkipped('the sqlite extension is not available');
        }

        if (is_file('foo.db')) {
            unlink('foo.db');
        }

        $this->dbh = new \PDO('sqlite:foo.db');
        $this->dbh->exec('CREATE TABLE `user` (`id` int(11), `username` varchar(255) NOT NULL, `email` varchar(255) NOT NULL )');

        $data = array(
            array(1, 'john', 'john@foo.bar'),
            array(2, 'john 2', 'john@foo.bar'),
            array(3, 'john 3', 'john@foo.bar'),
        );

        foreach ($data as $user) {
            $query = $this->dbh->prepare('INSERT INTO user (id, username, email) VALUES(?, ?, ?)');

            $query->execute($user);
        }
    }

    public function tearDown()
    {
        unlink('foo.db');
    }

    public function testHandler()
    {
        $stm = $this->dbh->prepare('SELECT id, username, email FROM user');
        $stm->execute();

        $iterator = new PDOStatementSourceIterator($stm);

        $data = array();
        foreach ($iterator as $user) {
            $data[] = $user;
        }

        $this->assertEquals(3, count($data));
    }
}
