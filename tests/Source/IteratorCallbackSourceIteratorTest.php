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
use Sonata\Exporter\Source\IteratorCallbackSourceIterator;
use Sonata\Exporter\Source\IteratorSourceIterator;

final class IteratorCallbackSourceIteratorTest extends TestCase
{
    private IteratorCallbackSourceIterator $sourceIterator;

    /**
     * @var \ArrayIterator<int, array{int}>
     */
    private \ArrayIterator $iterator;

    protected function setUp(): void
    {
        $this->iterator = new \ArrayIterator([[0], [1], [2], [3]]);
        $this->sourceIterator = new IteratorCallbackSourceIterator($this->iterator, static function (array $data): array {
            $data[0] = 1 << $data[0];

            return $data;
        });
    }

    public function testTransformer(): void
    {
        $result = [1, 2, 4, 8];

        foreach ($this->sourceIterator as $key => $value) {
            static::assertSame([$result[$key]], $value);
        }
    }

    public function testExtends(): void
    {
        static::assertInstanceOf(IteratorSourceIterator::class, $this->sourceIterator);
    }

    public function testGetIterator(): void
    {
        static::assertSame($this->iterator, $this->sourceIterator->getIterator());
    }
}
