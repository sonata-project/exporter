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
use Sonata\Exporter\Source\ArraySourceIterator;

class ArraySourceIteratorTest extends TestCase
{
    public function testHandler(): void
    {
        $data = [
            ['john 1', 'doe', '1'],
            ['john 2', 'doe', '1'],
            ['john 3', 'doe', '1'],
            ['john 4', 'doe', '1'],
        ];

        $iterator = new ArraySourceIterator($data);

        foreach ($iterator as $value) {
            $this->assertTrue(is_array($value));
            $this->assertEquals(3, count($value));
        }
    }
}
