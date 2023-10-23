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

namespace Sonata\Exporter\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Formatter\DateIntervalFormatter;
use Sonata\Exporter\Writer\InMemoryWriter;

final class DateIntervalFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFormatterCases
     */
    public function testFormatter(\DateInterval $dateInterval, string $expected): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'dateInterval' => $dateInterval,
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter(new DateIntervalFormatter());
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertSame($expected, $firstItem['dateInterval']);

        $writer->close();
    }

    /**
     * @phpstan-return iterable<array{0: \DateInterval, 1: string}>
     */
    public function provideFormatterCases(): iterable
    {
        yield [new \DateInterval('P1Y'), 'P1Y'];
        yield [new \DateInterval('P1M'), 'P1M'];
        yield [new \DateInterval('P1D'), 'P1D'];
        yield [new \DateInterval('PT1H'), 'PT1H'];
        yield [new \DateInterval('PT1M'), 'PT1M'];
        yield [new \DateInterval('PT1S'), 'PT1S'];
        yield [new \DateInterval('P1Y1M'), 'P1Y1M'];
        yield [new \DateInterval('P1Y1M1D'), 'P1Y1M1D'];
        yield [new \DateInterval('P1Y1M1DT1H'), 'P1Y1M1DT1H'];
        yield [new \DateInterval('P1Y1M1DT1H1M'), 'P1Y1M1DT1H1M'];
        yield [new \DateInterval('P1Y1M1DT1H1M1S'), 'P1Y1M1DT1H1M1S'];
        yield [new \DateInterval('P0Y'), 'P0Y'];
        yield [new \DateInterval('PT0S'), 'P0Y'];
    }
}
