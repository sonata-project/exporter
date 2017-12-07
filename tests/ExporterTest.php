<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Tests;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Exporter;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\JsonWriter;
use Sonata\Exporter\Writer\XlsWriter;
use Sonata\Exporter\Writer\XmlWriter;

class ExporterTest extends TestCase
{
    public function testFilter(): void
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Invalid "foo" format');
        $source = $this->createMock('Sonata\Exporter\Source\SourceIteratorInterface');
        $writer = $this->createMock('Sonata\Exporter\Writer\TypedWriterInterface');

        $exporter = new Exporter([$writer]);
        $exporter->getResponse('foo', 'foo', $source);
    }

    public function testConstructorRejectsNonTypedWriters(): void
    {
        $this->expectException(
            version_compare(PHP_VERSION, '7.0.0', '<') ? 'PHPUnit_Framework_Error' : 'TypeError'
        );
        $this->expectExceptionMessage(
            'must implement interface'
        );
        new Exporter(['Not even an object']);
    }

    public function testGetAvailableFormats(): void
    {
        $writer = $this->createMock('Sonata\Exporter\Writer\TypedWriterInterface');
        $writer->expects($this->once())
            ->method('getFormat')
            ->willReturn('whatever');
        $exporter = new Exporter([$writer]);
        $this->assertSame(['whatever'], $exporter->getAvailableFormats());
    }

    /**
     * @dataProvider getGetResponseTests
     */
    public function testGetResponse($format, $filename, $contentType, $expectedOutput): void
    {
        $source = new ArraySourceIterator([
            ['foo' => 'bar'],
        ]);
        $writer = $this->createMock('Sonata\Exporter\Writer\TypedWriterInterface');
        $writer->expects($this->any())
            ->method('getFormat')
            ->willReturn('made-up');
        $writer->expects($this->any())
            ->method('getDefaultMimeType')
            ->willReturn('application/made-up');

        $exporter = new Exporter([
            new CsvWriter('php://output', ',', '"', '', true, true),
            new JsonWriter('php://output'),
            new XlsWriter('php://output'),
            new XmlWriter('php://output'),
            $writer,
        ]);
        $response = $exporter->getResponse($format, $filename, $source);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertSame($contentType, $response->headers->get('Content-Type'));
        $this->assertSame('attachment; filename="'.$filename.'"', $response->headers->get('Content-Disposition'));
        $this->expectOutputRegex($expectedOutput);
        $response->sendContent();
    }

    public function getGetResponseTests()
    {
        return [
            ['json', 'foo.json', 'application/json', '#foo#'],
            ['xml', 'foo.xml', 'text/xml', '#foo#'],
            ['xls', 'foo.xls', 'application/vnd.ms-excel', '#foo#'],
            ['csv', 'foo.csv', 'text/csv', '#foo#'],
            ['made-up', 'foo.made-up', 'application/made-up', '##'],
        ];
    }
}
