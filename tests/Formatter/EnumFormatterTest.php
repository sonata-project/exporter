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
use Sonata\Exporter\Formatter\EnumFormatter;
use Sonata\Exporter\Tests\Source\Fixtures\Element;
use Sonata\Exporter\Tests\Source\Fixtures\Suit;
use Sonata\Exporter\Writer\InMemoryWriter;

/**
 * @requires PHP >= 8.1
 */
final class EnumFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFormatterCases
     */
    public function testFormatter(\UnitEnum $enumCase, EnumFormatter $formatter, string $expected): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'choice' => $enumCase,
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter($formatter);
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertSame($expected, $firstItem['choice']);

        $writer->close();
    }

    /**
     * @phpstan-return iterable<array{0: \UnitEnum, 1: EnumFormatter, 2: string}>
     */
    public function provideFormatterCases(): iterable
    {
        yield [Element::Hydrogen, new EnumFormatter(), 'Hydrogen'];
        yield [Suit::Diamonds, new EnumFormatter(), 'D'];
        yield [Suit::Diamonds, new EnumFormatter(false), 'Diamonds'];
    }
}
