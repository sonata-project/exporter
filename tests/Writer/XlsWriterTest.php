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

namespace Sonata\Exporter\Tests\Writer;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Writer\XlsWriter;

final class XlsWriterTest extends TestCase
{
    private string $filename;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filename = 'foobar.xls';

        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    protected function tearDown(): void
    {
        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testValidDataFormat(): void
    {
        $writer = new XlsWriter($this->filename, false);
        $writer->open();

        $writer->write(['firstname' => 'john "2', 'lastname' => 'doe', 'age' => '1']);
        $writer->close();

        $expected = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name=ProgId content=Excel.Sheet><meta name=Generator content="https://github.com/sonata-project/exporter"></head><body><table><tr><td>john "2</td><td>doe</td><td>1</td></tr></table></body></html>';

        static::assertSame($expected, trim(file_get_contents($this->filename)));
    }

    public function testWithHeaders(): void
    {
        $writer = new XlsWriter($this->filename);
        $writer->open();

        $writer->write(['firtname' => 'john "2', 'surname' => 'doe', 'year' => '1']);
        $writer->close();

        $expected = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name=ProgId content=Excel.Sheet><meta name=Generator content="https://github.com/sonata-project/exporter"></head><body><table><tr><th>firtname</th><th>surname</th><th>year</th></tr><tr><td>john "2</td><td>doe</td><td>1</td></tr></table></body></html>';

        static::assertSame($expected, trim(file_get_contents($this->filename)));
    }
}
