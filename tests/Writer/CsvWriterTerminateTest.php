<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test\Writer;

use Exporter\Test\AbstractTypedWriterTestCase;
use Exporter\Writer\CsvWriter;
use Exporter\Writer\CsvWriterTerminate;

class CsvWriterTerminateTest extends AbstractTypedWriterTestCase
{
    protected $filename;

    public function setUp()
    {
        parent::setUp();
        $this->filename = 'foobar.csv';

        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function tearDown()
    {
        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testFilter()
    {
        $file = fopen($this->filename, 'wb', false);
        stream_filter_register('filter', CsvWriterTerminate::class);
        stream_filter_append($file, 'filter', STREAM_FILTER_WRITE, ['terminate' => "\r\n"]);
        @fputcsv($file, ['john', 'doe', '1']);
        @fputcsv($file, ['john', 'doe', '2']);
        fclose($file);

        $expected = "john,doe,1\r\njohn,doe,2";

        $this->assertEquals($expected, trim(file_get_contents($this->filename)));
    }

    protected function getWriter()
    {
        return new CsvWriter('/tmp/whatever.csv');
    }
}
