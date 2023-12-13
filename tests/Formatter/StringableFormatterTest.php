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
use Sonata\Exporter\Formatter\StringableFormatter;
use Sonata\Exporter\Tests\Source\Fixtures\ObjectWithToString;
use Sonata\Exporter\Writer\InMemoryWriter;

final class StringableFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFormatterCases
     */
    public function testFormatter(object $value, string|object $expected): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'object' => $value,
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter(new StringableFormatter());
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertSame($expected, $firstItem['object']);

        $writer->close();
    }

    /**
     * @phpstan-return iterable<array{0: object, 1: string|object}>
     */
    public function provideFormatterCases(): iterable
    {
        yield [new ObjectWithToString('object with to string'), 'object with to string'];

        $nonStringableObject = new \stdClass();

        yield [$nonStringableObject, $nonStringableObject];
    }
}
