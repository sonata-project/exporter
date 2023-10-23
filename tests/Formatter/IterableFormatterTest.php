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
use Sonata\Exporter\Formatter\IterableFormatter;
use Sonata\Exporter\Writer\InMemoryWriter;

final class IterableFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFormatterCases
     *
     * @param iterable<int|string, mixed> $value
     */
    public function testFormatter(iterable $value, string $expected): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'iterable' => $value,
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter(new IterableFormatter());
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertSame($expected, $firstItem['iterable']);

        $writer->close();
    }

    /**
     * @phpstan-return iterable<array{0: iterable<int|string, mixed>, 1: string}>
     */
    public function provideFormatterCases(): iterable
    {
        yield [[1, 2, 3], '[1, 2, 3]'];
        yield [['a', 'b', 'c'], '[a, b, c]'];
        yield [
            [
                'a' => 'A',
                'b' => 'B',
                'c' => 'C',
            ],
            '[A, B, C]',
        ];
        yield [new \ArrayIterator([1, 2, 3]), '[1, 2, 3]'];
        yield [(static function (): \Generator { yield from [1, 2, 3]; })(), '[1, 2, 3]'];
    }
}
