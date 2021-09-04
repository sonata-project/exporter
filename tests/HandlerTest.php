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

namespace Sonata\Exporter\Test;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Handler;

class HandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $source = $this->createMock('Sonata\Exporter\Source\SourceIteratorInterface');
        $writer = $this->createMock('Sonata\Exporter\Writer\WriterInterface');
        $writer->expects(static::once())->method('open');
        $writer->expects(static::once())->method('close');

        $exporter = new Handler($source, $writer);
        $exporter->export();
    }
}
