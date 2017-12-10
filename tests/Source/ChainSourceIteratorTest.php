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

namespace Sonata\Exporter\Test\Source;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\ChainSourceIterator;

class ChainSourceIteratorTest extends TestCase
{
    public function testIterator(): void
    {
        $source = $this->createMock('Sonata\Exporter\Source\SourceIteratorInterface');

        $iterator = new ChainSourceIterator([$source]);

        foreach ($iterator as $data) {
        }
    }
}
