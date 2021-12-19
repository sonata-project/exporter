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
use Sonata\Exporter\Writer\GsaFeedWriter;

/**
 * Tests the GSA feed writer class.
 *
 * @author Rémi Marseille <marseille@ekino.com>
 */
final class GsaFeedWriterTest extends TestCase
{
    /**
     * @var \SplFileInfo
     */
    private $folder;

    /**
     * @var string
     */
    private $dtd;

    /**
     * @var string
     */
    private $datasource;

    /**
     * @var string
     */
    private $feedtype;

    /**
     * Creates the folder useful to this test.
     */
    protected function setUp(): void
    {
        $path = sys_get_temp_dir().\DIRECTORY_SEPARATOR.'sonata_exporter_test';
        $this->folder = new \SplFileInfo($path);

        $this->tearDown();

        mkdir($path);

        $this->dtd = 'http://gsa.example.com/gsafeed.dtd';
        $this->datasource = 'default_collection';
        $this->feedtype = 'metadata-and-url';
    }

    /**
     * Deletes the generated XML and the created folder.
     */
    protected function tearDown(): void
    {
        if ($this->folder->getRealPath()) {
            foreach ($this->getFiles() as $file) {
                unlink($file);
            }

            rmdir($this->folder->getRealPath());
        }
    }

    public function testNonExistentFolder(): void
    {
        $this->expectException(\RuntimeException::class);

        $writer = new GsaFeedWriter(new \SplFileInfo('foo'), $this->dtd, $this->datasource, $this->feedtype);
        $writer->open();
    }

    /**
     * Tests a simple write case.
     */
    public function testSimpleWrite(): void
    {
        $writer = new GsaFeedWriter($this->folder, $this->dtd, $this->datasource, $this->feedtype);
        $writer->open();
        $writer->write([
            'url' => 'https://sonata-project.org/about',
            'mime_type' => 'text/html',
            'action' => 'add',
        ]);
        $writer->write([
            'url' => 'https://sonata-project.org/bundles/',
            'mime_type' => 'text/html',
            'action' => 'delete',
        ]);
        $writer->close();

        $generatedFiles = $this->getFiles();

        static::assertCount(1, $generatedFiles);
        static::assertSame($this->folder->getRealPath().'/feed_00001.xml', $generatedFiles[0]);

        // this will throw an exception if the xml is invalid
        new \SimpleXMLElement(file_get_contents($generatedFiles[0]), \LIBXML_PARSEHUGE);

        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE gsafeed PUBLIC "-//Google//DTD GSA Feeds//EN" "$this->dtd">
<gsafeed>
    <header>
        <datasource>$this->datasource</datasource>
        <feedtype>$this->feedtype</feedtype>
    </header>

    <group>
        <record url="https://sonata-project.org/about" mimetype="text/html" action="add"/>
        <record url="https://sonata-project.org/bundles/" mimetype="text/html" action="delete"/>
    </group>
</gsafeed>
XML;

        static::assertSame(trim($expected), file_get_contents($generatedFiles[0]));
    }

    /**
     * Tests the writer limit.
     */
    public function testLimitSize(): void
    {
        $writer = new GsaFeedWriter($this->folder, $this->dtd, $this->datasource, $this->feedtype);
        $writer->open();

        foreach (range(0, GsaFeedWriter::LIMIT_SIZE / 8196) as $i) {
            $writer->write([
                'url' => str_repeat('x', 8196),
                'mime_type' => 'text/html',
                'action' => 'add',
            ]);
        }

        $writer->close();

        $generatedFiles = $this->getFiles();

        static::assertCount(2, $generatedFiles);

        // this will throw an exception if the xml is invalid
        new \SimpleXMLElement(file_get_contents($generatedFiles[0]), \LIBXML_PARSEHUGE);
        new \SimpleXMLElement(file_get_contents($generatedFiles[1]), \LIBXML_PARSEHUGE);

        $info = stat($generatedFiles[0]);

        static::assertLessThan(GsaFeedWriter::LIMIT_SIZE, $info['size']);
    }

    /**
     * Gets an array of files of the main folder.
     *
     * @return array
     */
    public function getFiles()
    {
        $files = glob($this->folder->getRealPath().'/*.xml');

        sort($files);

        return $files;
    }
}
