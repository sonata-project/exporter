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

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sonata\Exporter\Test\AbstractTypedWriterTestCase;
use Sonata\Exporter\Writer\TypedWriterInterface;
use Sonata\Exporter\Writer\XlsxWriter;

final class XlsxWriterTest extends AbstractTypedWriterTestCase
{
    protected $filename;
    protected $filenameCsv;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filename = 'foobar.xlsx';
        $this->filenameCsv = 'foobar.csv';

        if (is_file($this->filename)) {
            unlink($this->filename);
        }

        if (is_file($this->filenameCsv)) {
            unlink($this->filenameCsv);
        }
    }

    protected function tearDown(): void
    {
        if (is_file($this->filename)) {
            unlink($this->filename);
        }

        if (is_file($this->filenameCsv)) {
            unlink($this->filenameCsv);
        }
    }

    public function testValidDataFormat(): void
    {
        $writer = new XlsxWriter($this->filename, false);
        $writer->open();

        $writer->write(['john "2', 'doe', '1']);
        $writer->close();

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($this->filename);

        $excelWriter = IOFactory::createWriter($spreadsheet, 'Csv');
        $excelWriter->save($this->filenameCsv);

        $expected = sprintf('"john ""2","doe","1"%s', PHP_EOL);

        static::assertSame($expected, file_get_contents($this->filenameCsv));

    }

    public function testWithHeaders(): void
    {
        $writer = new XlsxWriter($this->filename);
        $writer->open();

        $writer->write(['firtname' => 'john "2', 'surname' => 'doe', 'year' => '1']);
        $writer->close();

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($this->filename);

        $excelWriter = IOFactory::createWriter($spreadsheet, 'Csv');
        $excelWriter->save($this->filenameCsv);

        $expected = sprintf('"firtname","surname","year"%s"john ""2","doe","1"%s', PHP_EOL, PHP_EOL);

        static::assertSame($expected, file_get_contents($this->filenameCsv));
    }

    protected function getWriter(): TypedWriterInterface
    {
        return new XlsxWriter('/tmp/whatever.xlsx', false);
    }
}
