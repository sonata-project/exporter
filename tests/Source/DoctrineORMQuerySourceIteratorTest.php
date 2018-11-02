<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test\Source;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Exporter\Test\Source\Fixtures\DoctrineORMQuerySourceIterator;
use Exporter\Test\Source\Fixtures\ObjectWithToString;
use PHPUnit\Framework\TestCase;

/**
 * @author Joseph Maarek <josephmaarek@gmail.com>
 */
final class DoctrineORMQuerySourceIteratorTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    protected function setUp()
    {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->method('getConfiguration')->willReturn(new Configuration());
        $this->query = new Query($entityManager);
    }

    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue($value, $expected, $dateFormat = 'r')
    {
        $iterator = new DoctrineORMQuerySourceIterator($this->query, [], $dateFormat);
        $this->assertSame($expected, $iterator->getValue($value));
    }

    public function getValueProvider()
    {
        $datetime = new \DateTime();
        $dateTimeImmutable = new \DateTimeImmutable();

        $data = [
            [[1, 2, 3], null],
            [new \ArrayIterator([1, 2, 3]), null],
            [$datetime, $datetime->format('r')],
            [$datetime, $datetime->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'],
            [123, 123],
            ['123', '123'],
            [new ObjectWithToString('object with to string'), 'object with to string'],
            [$dateTimeImmutable, $dateTimeImmutable->format('r')],
            [$dateTimeImmutable, $dateTimeImmutable->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'],
            [new \DateInterval('P1Y'), 'P1Y'],
            [new \DateInterval('P1M'), 'P1M'],
            [new \DateInterval('P1D'), 'P1D'],
            [new \DateInterval('PT1H'), 'PT1H'],
            [new \DateInterval('PT1M'), 'PT1M'],
            [new \DateInterval('PT1S'), 'PT1S'],
            [new \DateInterval('P1Y1M'), 'P1Y1M'],
            [new \DateInterval('P1Y1M1D'), 'P1Y1M1D'],
            [new \DateInterval('P1Y1M1DT1H'), 'P1Y1M1DT1H'],
            [new \DateInterval('P1Y1M1DT1H1M'), 'P1Y1M1DT1H1M'],
            [new \DateInterval('P1Y1M1DT1H1M1S'), 'P1Y1M1DT1H1M1S'],
            [new \DateInterval('P0Y'), 'P0Y'],
            [new \DateInterval('PT0S'), 'P0Y'],
        ];

        return $data;
    }
}
