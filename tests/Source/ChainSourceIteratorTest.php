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
use Sonata\Exporter\Source\ChainSourceIterator;

class ChainSourceIteratorTest extends TestCase
{
    public function testIterator()
    {
        $source = $this->createMock('Exporter\Source\SourceIteratorInterface');

        $iterator = new ChainSourceIterator([$source]);

        foreach ($iterator as $data) {
        }
    }
}
