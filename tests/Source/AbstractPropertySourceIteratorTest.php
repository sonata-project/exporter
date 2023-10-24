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
use Sonata\Exporter\Source\AbstractPropertySourceIterator;
use Sonata\Exporter\Tests\Source\Fixtures\Element;
use Sonata\Exporter\Tests\Source\Fixtures\ObjectWithToString;
use Sonata\Exporter\Tests\Source\Fixtures\Suit;

final class AbstractPropertySourceIteratorTest extends TestCase
{
    /**
     * @dataProvider provideGetValueCases
     */
    public function testGetValue(mixed $value, mixed $expected, string $dateFormat = 'r', bool $useBackedEnumValue = true): void
    {
        $iterator = new class([], $dateFormat, $useBackedEnumValue) extends AbstractPropertySourceIterator {
            public function rewind(): void
            {
                $this->iterator = new \ArrayIterator();
                $this->iterator->rewind();
            }

            public function getValue(mixed $value): bool|int|float|string|null
            {
                return parent::getValue($value);
            }
        };

        static::assertSame($expected, $iterator->getValue($value));
    }

    /**
     * @return iterable<array{0: mixed, 1: mixed, 2?: string, 3?: bool}>
     */
    public function provideGetValueCases(): iterable
    {
        $datetime = new \DateTime();
        $dateTimeImmutable = new \DateTimeImmutable();

        yield [[1, 2, 3], '[1, 2, 3]'];
        yield [new \ArrayIterator([1, 2, 3]), '[1, 2, 3]'];
        yield [(static function (): \Generator { yield from [1, 2, 3]; })(), '[1, 2, 3]'];
        yield [$datetime, $datetime->format('r')];
        yield [$datetime, $datetime->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'];
        yield [123, 123];
        yield ['123', '123'];
        yield [new ObjectWithToString('object with to string'), 'object with to string'];
        yield [$dateTimeImmutable, $dateTimeImmutable->format('r')];
        yield [$dateTimeImmutable, $dateTimeImmutable->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'];
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

        if (\PHP_VERSION_ID < 80100) {
            return;
        }

        yield [Element::Hydrogen, 'Hydrogen'];
        yield [Suit::Diamonds, 'D'];
        yield [Suit::Diamonds, 'Diamonds', 'r', false];
    }
}
