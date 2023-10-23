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
use Sonata\Exporter\Tests\Source\Fixtures\Suit;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;

final class AbstractPropertySourceIteratorTest extends TestCase
{
    use ExpectDeprecationTrait;

    /**
     * @dataProvider provideLegacyGetValueCases
     *
     * @group legacy
     */
    public function testLegacyGetValue(mixed $value, mixed $expected, ?string $dateFormat = null, ?bool $useBackedEnumValue = null): void
    {
        if (null !== $dateFormat) {
            $this->expectDeprecation(
                'Passing a value as argument 2 in "Sonata\Exporter\Source\AbstractPropertySourceIterator::__construct()" is deprecated since'
                .' sonata-project/exporter 3.x and will be removed in version 4.0, use "Sonata\Exporter\Formatter\DateTimeFormatter" instead.'
            );
        }

        if (null !== $useBackedEnumValue) {
            $this->expectDeprecation(
                'Passing a value as argument 3 in "Sonata\Exporter\Source\AbstractPropertySourceIterator::__construct()" is deprecated since'
                .' sonata-project/exporter 3.x and will be removed in version 4.0, use "Sonata\Exporter\Formatter\EnumFormatter" instead.'
            );
        }

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
    public function provideLegacyGetValueCases(): iterable
    {
        $datetime = new \DateTime();
        $dateTimeImmutable = new \DateTimeImmutable();

        yield [$datetime, $datetime->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'];
        yield [$dateTimeImmutable, $dateTimeImmutable->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'];

        if (\PHP_VERSION_ID < 80100) {
            return;
        }

        yield [Suit::Diamonds, 'Diamonds', 'r', false];
    }
}
