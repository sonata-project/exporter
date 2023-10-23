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

namespace Sonata\Exporter\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Formatter\BoolFormatter;
use Sonata\Exporter\Writer\InMemoryWriter;

final class BoolFormatterTest extends TestCase
{
    public function testFotmatter(): void
    {
        $data = ['john', 'doe', false, true];
        $expected = [
            ['john', 'doe', 'no', 'yes'],
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter(new BoolFormatter('yes', 'no'));
        $writer->open();
        $writer->write($data);

        static::assertSame($expected, $writer->getElements());

        $writer->close();
    }
}
