<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Test\Writer;

use Sonata\Exporter\Test\AbstractTypedWriterTestCase;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;
use Sonata\Exporter\Exception\InvalidDataFormatException;

class CsvWriterTest extends AbstractTypedWriterTestCase
{
    protected $filename;

    public function setUp(): void
    {
        parent::setUp();
        $this->filename = 'foobar.csv';

        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function tearDown(): void
    {
        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testInvalidDataFormat(): void
    {
        $this->expectException(InvalidDataFormatException::class);

        $writer = new CsvWriter($this->filename, ',', '', '\\', false);
        $writer->open();

        $writer->write(['john "2', 'doe', '1']);
    }

    public function testEnclosureFormating(): void
    {
        $writer = new CsvWriter($this->filename, ',', '"', '\\', false);
        $writer->open();

        $writer->write([' john , ""2"', 'doe', '1']);

        $writer->close();

        $expected = '" john , """"2""",doe,1';

        $this->assertEquals($expected, trim(file_get_contents($this->filename)));
    }

    public function testEnclosureFormatingWithExcel(): void
    {
        $writer = new CsvWriter($this->filename, ',', '"', '', false);
        $writer->open();

        $writer->write(['john , ""2"', 'doe ', '1']);

        $writer->close();

        $expected = '"john , """"2""","doe ",1';

        $this->assertEquals($expected, trim(file_get_contents($this->filename)));
    }

    public function testWithHeaders(): void
    {
        $writer = new CsvWriter($this->filename, ',', '"', '', true);
        $writer->open();

        $writer->write(['name' => 'john , ""2"', 'surname' => 'doe ', 'year' => '2001']);

        $writer->close();

        $expected = 'name,surname,year'."\n".'"john , """"2""","doe ",2001';

        $this->assertEquals($expected, trim(file_get_contents($this->filename)));
    }

    public function testWithBom(): void
    {
        $writer = new CsvWriter($this->filename, ',', '"', '', true, true);
        $writer->open();

        $writer->write(['name' => 'Rémi , ""2"', 'surname' => 'doe ', 'year' => '2001']);

        $writer->close();

        $expected = chr(0xEF).chr(0xBB).chr(0xBF).'name,surname,year'."\n".'"Rémi , """"2""","doe ",2001';
        $this->assertEquals($expected, trim(file_get_contents($this->filename)));
    }

    protected function getWriter(): TypedWriterInterface
    {
        return new CsvWriter('/tmp/whatever.csv');
    }
}
