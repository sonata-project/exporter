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
use Sonata\Exporter\Formatter\DateTimeFormatter;
use Sonata\Exporter\Writer\InMemoryWriter;

final class DateTimeFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFormatterCases
     */
    public function testFormatter(\DateTimeInterface $dateTime, DateTimeFormatter $formatter, string $expected): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'date' => $dateTime,
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter($formatter);
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertSame($expected, $firstItem['date']);

        $writer->close();
    }

    /**
     * @phpstan-return iterable<array{0: \DateTimeInterface, 1: DateTimeFormatter, 2: string}>
     */
    public function provideFormatterCases(): iterable
    {
        $dateTimeImmutable = new \DateTimeImmutable('1986-03-22 21:45:00');

        yield [$dateTimeImmutable, new DateTimeFormatter(), 'Sat, 22 Mar 1986 21:45:00 +0000'];
        yield [$dateTimeImmutable, new DateTimeFormatter('Y-m-d H:i:s'), '1986-03-22 21:45:00'];
        yield [$dateTimeImmutable, new DateTimeFormatter('Y-m-d'), '1986-03-22'];

        $dateTime = new \DateTime('1986-03-22 21:45:00');

        yield [$dateTime, new DateTimeFormatter(), 'Sat, 22 Mar 1986 21:45:00 +0000'];
        yield [$dateTime, new DateTimeFormatter('Y-m-d H:i:s'), '1986-03-22 21:45:00'];
        yield [$dateTime, new DateTimeFormatter('Y-m-d'), '1986-03-22'];
    }
}
