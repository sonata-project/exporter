<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test;

use Exporter\Handler;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    public function testHandler()
    {
        $source = $this->createMock('Exporter\Source\SourceIteratorInterface');
        $writer = $this->createMock('Exporter\Writer\WriterInterface');
        $writer->expects($this->once())->method('open');
        $writer->expects($this->once())->method('close');

        $exporter = new Handler($source, $writer);
        $exporter->export();
    }
}
