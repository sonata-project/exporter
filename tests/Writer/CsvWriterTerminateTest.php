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

use Sonata\Exporter\Test\AbstractTypedWriterTestCase;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\CsvWriterTerminate;
use Sonata\Exporter\Writer\TypedWriterInterface;

final class CsvWriterTerminateTest extends AbstractTypedWriterTestCase
{
    /**
     * @var string
     */
    protected $filename;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filename = 'foobar.csv';

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

    public function testFilter(): void
    {
        $file = fopen($this->filename, 'w', false);
        stream_filter_register('filter', CsvWriterTerminate::class);
        stream_filter_append($file, 'filter', \STREAM_FILTER_WRITE, ['terminate' => "\r\n"]);
        @fputcsv($file, ['john', 'doe', '1']);
        @fputcsv($file, ['john', 'doe', '2']);
        fclose($file);

        $expected = "john,doe,1\r\njohn,doe,2";

        static::assertSame($expected, trim(file_get_contents($this->filename)));
    }

    protected function getWriter(): TypedWriterInterface
    {
        return new CsvWriter('/tmp/whatever.csv');
    }
}
