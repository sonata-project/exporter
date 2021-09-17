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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\IteratorSourceIterator;

class IteratorSourceIteratorTest extends TestCase
{
    /**
     * @var IteratorSourceIterator
     */
    protected $sourceIterator;

    /**
     * @var \Iterator&MockObject
     */
    protected $iterator;

    protected function setUp(): void
    {
        $this->iterator = $this->createMock(\Iterator::class);
        $this->sourceIterator = new IteratorSourceIterator($this->iterator);
    }

    public function testGetIterator(): void
    {
        static::assertSame($this->iterator, $this->sourceIterator->getIterator());
    }

    public function testCurrent(): void
    {
        $this->iterator
            ->expects(static::once())
            ->method('current')
            ->willReturn(['current']);

        static::assertSame(['current'], $this->sourceIterator->current());
    }

    public function testNext(): void
    {
        $this->iterator
            ->expects(static::once())
            ->method('next');

        $this->sourceIterator->next();
    }

    public function testKey(): void
    {
        $this->iterator
            ->expects(static::once())
            ->method('key')
            ->willReturn('key');

        static::assertSame('key', $this->sourceIterator->key());
    }

    public function testValid(): void
    {
        $this->iterator
            ->expects(static::once())
            ->method('valid')
            ->willReturn(true);

        static::assertTrue($this->sourceIterator->valid());
    }

    public function testRewind(): void
    {
        $this->iterator
            ->expects(static::once())
            ->method('rewind');

        $this->sourceIterator->rewind();
    }
}
