<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Test\Source;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\IteratorCallbackSourceIterator;

class IteratorCallbackSourceIteratorTest extends TestCase
{
    /** @var IteratorCallbackSourceIterator */
    protected $sourceIterator;

    /** @var \ArrayIterator */
    protected $iterator;

    protected function setUp(): void
    {
        $this->iterator = new \ArrayIterator([[0], [1], [2], [3]]);
        $this->sourceIterator = new IteratorCallbackSourceIterator($this->iterator, function ($data) {
            $data[0] = 1 << $data[0];

            return $data;
        });
    }

    public function testTransformer(): void
    {
        $result = [1, 2, 4, 8];

        foreach ($this->sourceIterator as $key => $value) {
            $this->assertEquals([$result[$key]], $value);
        }
    }

    public function testExtends(): void
    {
        $this->assertInstanceOf('Sonata\Exporter\Source\IteratorSourceIterator', $this->sourceIterator);
    }

    public function testGetIterator(): void
    {
        $this->assertSame($this->iterator, $this->sourceIterator->getIterator());
    }
}
