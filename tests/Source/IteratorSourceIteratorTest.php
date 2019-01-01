<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test\Source;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\IteratorSourceIterator;

class IteratorSourceIteratorTest extends TestCase
{
    /**
     * @var IteratorSourceIterator
     */
    protected $sourceIterator;
    /**
     * @var \Iterator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $iterator;

    protected function setUp()
    {
        $this->iterator = $this->createMock('Iterator');
        $this->sourceIterator = new IteratorSourceIterator($this->iterator);
    }

    public function testGetIterator()
    {
        self::assertSame($this->iterator, $this->sourceIterator->getIterator());
    }

    public function testCurrent()
    {
        $this->iterator
            ->expects(self::once())
            ->method('current')
            ->will($this->returnValue(['current']));

        self::assertEquals(['current'], $this->sourceIterator->current());
    }

    public function testNext()
    {
        $this->iterator
            ->expects(self::once())
            ->method('next');

        $this->sourceIterator->next();
    }

    public function testKey()
    {
        $this->iterator
            ->expects(self::once())
            ->method('key')
            ->will($this->returnValue('key'));

        self::assertEquals('key', $this->sourceIterator->key());
    }

    public function testValid()
    {
        $this->iterator
            ->expects(self::once())
            ->method('valid')
            ->will($this->returnValue(true));

        self::assertTrue($this->sourceIterator->valid());
    }

    public function testRewind()
    {
        $this->iterator
            ->expects(self::once())
            ->method('rewind');

        $this->sourceIterator->rewind();
    }
}
