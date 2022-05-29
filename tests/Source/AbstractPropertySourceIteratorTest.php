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
use Sonata\Exporter\Tests\Source\Fixtures\ObjectWithToString;

final class AbstractPropertySourceIteratorTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(mixed $value, mixed $expected, string $dateFormat = 'r'): void
    {
        $iterator = new class([], $dateFormat) extends AbstractPropertySourceIterator {
            public function rewind(): void
            {
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
     * @return array<array{0: mixed, 1: mixed, 2?: string}>
     */
    public function getValueProvider(): array
    {
        $datetime = new \DateTime();
        $dateTimeImmutable = new \DateTimeImmutable();

        $data = [
            [[1, 2, 3], '[1, 2, 3]'],
            [new \ArrayIterator([1, 2, 3]), '[1, 2, 3]'],
            [(static function (): \Generator { yield from [1, 2, 3]; })(), '[1, 2, 3]'],
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
