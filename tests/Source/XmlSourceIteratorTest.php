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

namespace Sonata\Exporter\Tests\Source;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\XmlSourceIterator;

class XmlSourceIteratorTest extends TestCase
{
    protected $filename;
    protected $filenameCustomTagNames;

    public function setUp(): void
    {
        $this->filename = 'source_xml.xml';
        $xml = '<?xml version="1.0" ?><datas><data><sku><![CDATA[123]]></sku><ean><![CDATA[1234567891234]]></ean><name><![CDATA[Product é]]></name></data><data><sku><![CDATA[124]]></sku><ean><![CDATA[1234567891235]]></ean><name><![CDATA[Product @]]></name></data><data><sku><![CDATA[125]]></sku><ean><![CDATA[1234567891236]]></ean><name><![CDATA[Product 3 ©]]></name></data></datas>';
        $this->createXmlFile($this->filename, $xml);

        // for custom tag names
        $this->filenameCustomTagNames = 'source_xml_custom_tag_names.xml';
        $xml = '<?xml version="1.0" ?><channel><item><sku><![CDATA[123]]></sku><ean><![CDATA[1234567891234]]></ean><name><![CDATA[Product é]]></name></item><item><sku><![CDATA[124]]></sku><ean><![CDATA[1234567891235]]></ean><name><![CDATA[Product @]]></name></item><item><sku><![CDATA[125]]></sku><ean><![CDATA[1234567891236]]></ean><name><![CDATA[Product 3 ©]]></name></item></channel>';
        $this->createXmlFile($this->filenameCustomTagNames, $xml);
    }

    public function tearDown(): void
    {
        unlink($this->filename);
        unlink($this->filenameCustomTagNames);
    }

    public function testHandler(): void
    {
        $iterator = new XmlSourceIterator($this->filename);

        $i = 0;
        foreach ($iterator as $value) {
            $this->assertIsArray($value);
            $this->assertCount(3, $value);
            $keys = array_keys($value);
            $this->assertSame($i, $iterator->key());
            $this->assertSame('sku', $keys[0]);
            $this->assertSame('ean', $keys[1]);
            $this->assertSame('name', $keys[2]);
            ++$i;
        }
        $this->assertSame(3, $i);
    }

    public function testRewind(): void
    {
        $iterator = new XmlSourceIterator($this->filename);

        $i = 0;
        foreach ($iterator as $value) {
            $this->assertIsArray($value);
            $this->assertCount(3, $value);
            ++$i;
        }
        $this->assertSame(3, $i);

        $i = 0;
        foreach ($iterator as $value) {
            $this->assertIsArray($value);
            $this->assertCount(3, $value);
            ++$i;
        }
        $this->assertSame(3, $i);
    }

    public function testCustomTagNames(): void
    {
        $iterator = new XmlSourceIterator($this->filenameCustomTagNames, 'channel', 'item');

        $i = 0;
        foreach ($iterator as $value) {
            $this->assertIsArray($value);
            $this->assertCount(3, $value);
            $keys = array_keys($value);
            $this->assertSame($i, $iterator->key());
            $this->assertSame('sku', $keys[0]);
            $this->assertSame('ean', $keys[1]);
            $this->assertSame('name', $keys[2]);
            ++$i;
        }
        $this->assertSame(3, $i);
    }

    protected function createXmlFile($filename, $content): void
    {
        if (is_file($filename)) {
            unlink($filename);
        }

        file_put_contents($filename, $content);
    }
}
