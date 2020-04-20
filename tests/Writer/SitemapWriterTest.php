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
use SimpleXMLElement;
use Sonata\Exporter\Writer\SitemapWriter;

class SitemapWriterTest extends TestCase
{
    /**
     * @var string
     */
    protected $folder;

    protected function setUp(): void
    {
        $this->folder = sys_get_temp_dir().'/sonata_exporter_test';

        $this->tearDown();

        mkdir($this->folder);
    }

    protected function tearDown(): void
    {
        foreach ($this->getFiles() as $file) {
            unlink($file);
        }

        if (is_dir($this->folder)) {
            rmdir($this->folder);
        }
    }

    public function testNonExistentFolder(): void
    {
        $this->expectException(\RuntimeException::class);

        $writer = new SitemapWriter('booo');
        $writer->open();
    }

    public function testSimpleWrite(): void
    {
        $writer = new SitemapWriter($this->folder);
        $writer->open();
        $writer->write([
            'url' => 'https://sonata-project.org/bundle/',
            'lastmod' => '2012-12-26',
            'change' => 'daily',
        ]);
        $writer->close();

        $generatedFiles = $this->getFiles();

        $this->assertCount(2, $generatedFiles);

        // this will throw an exception if the xml is invalid
        new SimpleXMLElement(file_get_contents($generatedFiles[1]));

        $expected = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>https://sonata-project.org/bundle/</loc><lastmod>2012-12-26</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
</urlset>
XML;

        $this->assertSame(trim($expected), file_get_contents($generatedFiles[1]));
    }

    public function testSimpleWriteAdvanced(): void
    {
        $writer = new SitemapWriter($this->folder, 'test', ['image'], false);
        $writer->open();
        $writer->write([
            'url' => 'https://sonata-project.org/bundle/',
            'lastmod' => '2012-12-26',
            'change' => 'daily',
            'type' => 'default',
        ]);
        $writer->write([
            'url' => 'https://sonata-project.org/bundle/',
            'lastmod' => '2012-12-26',
            'change' => 'weekly',
            'type' => 'image',
            'images' => [
                [
                    'url' => 'https://sonata-project.org/uploads/media/default/0001/01/thumb_1_default_small.jpg',
                    'caption' => 'sonata img',
                ],
            ],
        ]);
        $writer->close();

        $generatedFiles = $this->getFiles();

        $this->assertCount(1, $generatedFiles);
        $this->assertSame($this->folder.'/sitemap_test_00001.xml', $generatedFiles[0]);

        SitemapWriter::generateSitemapIndex($this->folder, 'https://sonata-project.org');

        $generatedFiles = $this->getFiles();

        $this->assertCount(2, $generatedFiles);

        // this will throw an exception if the xml is invalid
        new SimpleXMLElement(file_get_contents($generatedFiles[1]));

        $expected = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url><loc>https://sonata-project.org/bundle/</loc><lastmod>2012-12-26</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
    <url><loc>https://sonata-project.org/bundle/</loc><image:image><image:loc>https://sonata-project.org/uploads/media/default/0001/01/thumb_1_default_small.jpg</image:loc><image:caption>sonata img</image:caption></image:image></url>
</urlset>
XML;

        $this->assertSame(trim($expected), file_get_contents($generatedFiles[1]));
    }

    public function testLimitSize(): void
    {
        $writer = new SitemapWriter($this->folder);
        $writer->open();

        foreach (range(0, SitemapWriter::LIMIT_SIZE / 8196) as $i) {
            $writer->write([
                'url' => str_repeat('x', 8196),
                'lastmod' => 'now',
                'change' => 'daily',
            ]);
        }
        $writer->close();

        $generatedFiles = $this->getFiles();

        $this->assertCount(3, $generatedFiles);

        // this will throw an exception if the xml is invalid
        new SimpleXMLElement(file_get_contents($generatedFiles[1]));
        new SimpleXMLElement(file_get_contents($generatedFiles[2]));

        $info = stat($generatedFiles[1]);

        $this->assertLessThan(SitemapWriter::LIMIT_SIZE, $info['size']);
    }

    public function testLimitUrl(): void
    {
        $writer = new SitemapWriter($this->folder);
        $writer->open();

        foreach (range(1, SitemapWriter::LIMIT_URL + 1) as $i) {
            $writer->write([
                'url' => str_repeat('x', 40),
                'lastmod' => 'now',
                'change' => 'daily',
            ]);
        }
        $writer->close();

        $generatedFiles = $this->getFiles();

        $this->assertCount(3, $generatedFiles);

        // this will throw an exception if the xml is invalid
        $file1 = new SimpleXMLElement(file_get_contents($generatedFiles[1]));
        $file2 = new SimpleXMLElement(file_get_contents($generatedFiles[2]));

        $info = stat($generatedFiles[0]);

        $this->assertLessThan(SitemapWriter::LIMIT_SIZE, $info['size']);
        $this->assertSame(SitemapWriter::LIMIT_URL, \count($file1->children()));
        $this->assertCount(1, iterator_to_array($file2->children()));
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        $files = glob($this->folder.'/*.xml');

        sort($files);

        return $files;
    }
}
