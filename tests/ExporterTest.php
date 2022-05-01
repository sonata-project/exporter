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

namespace Sonata\Exporter\Tests;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Exporter;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\JsonWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;
use Sonata\Exporter\Writer\XlsWriter;
use Sonata\Exporter\Writer\XmlWriter;
use Symfony\Component\HttpFoundation\Response;

final class ExporterTest extends TestCase
{
    public function testFilter(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid "foo" format');
        $source = $this->createMock(\Iterator::class);
        $writer = $this->createMock(TypedWriterInterface::class);

        $exporter = new Exporter([$writer]);
        $exporter->getResponse('foo', 'foo', $source);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testConstructorRejectsNonTypedWriters(): void
    {
        $this->expectException(\TypeError::class);

        // @phpstan-ignore-next-line
        new Exporter(['Not even an object']);
    }

    public function testGetAvailableFormats(): void
    {
        $writer = $this->createMock(TypedWriterInterface::class);
        $writer->expects(static::once())
            ->method('getFormat')
            ->willReturn('whatever');
        $exporter = new Exporter([$writer]);
        static::assertSame(['whatever'], $exporter->getAvailableFormats());
    }

    /**
     * @dataProvider getGetResponseTests
     */
    public function testGetResponse(string $format, string $filename, string $contentType, string $expectedOutput): void
    {
        $source = new ArraySourceIterator([
            ['foo' => 'bar'],
        ]);
        $writer = $this->createMock(TypedWriterInterface::class);
        $writer->expects(static::any())
            ->method('getFormat')
            ->willReturn('made-up');
        $writer->expects(static::any())
            ->method('getDefaultMimeType')
            ->willReturn('application/made-up');

        $exporter = new Exporter([
            new CsvWriter('php://output', ',', '"', '\\', true, true),
            new JsonWriter('php://output'),
            new XlsWriter('php://output'),
            new XmlWriter('php://output'),
            $writer,
        ]);
        $response = $exporter->getResponse($format, $filename, $source);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame($contentType, $response->headers->get('Content-Type'));
        static::assertSame('attachment; filename="'.$filename.'"', $response->headers->get('Content-Disposition'));
        $this->expectOutputRegex($expectedOutput);
        $response->sendContent();
    }

    /**
     * @return array<array{string, string, string, string}>
     */
    public function getGetResponseTests(): array
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
