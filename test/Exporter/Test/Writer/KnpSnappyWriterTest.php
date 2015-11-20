<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test\Source;

use Exporter\Writer\KnpSnappyWriter;

/**
 * Tests the KnpSnapyWriter.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
final class KnpSnappyWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $wkhtmltopdf;

    public function setUp()
    {
        $this->filename = 'foobar.pdf';
        $this->wkhtmltopdf = __DIR__.'/../../../../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64';

        if (!is_executable($this->wkhtmltopdf)) {
            $this->markTestSkipped('The wkhtmltopdf binary is not available');
        }

        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function tearDown()
    {
        unlink($this->filename);
    }

    public function testWriteWithoutArgs()
    {
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdf, false);
        $writer->open();
        $writer->write(array('john "2', 'doe', '1'));
        $writer->close();

        $this->assertFileExists($this->filename);
    }

    public function testWithHeaders()
    {
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdf, true, false);

        $writer->open();
        $writer->write(array('firstname' => 'john "2', 'surname' => 'doe', 'year' => '1'));
        $writer->close();

        $this->assertAttributeContains('firstname', 'html', $writer);
        $this->assertFileExists($this->filename);
    }

    public function testWithBorders()
    {
        $borderedStyle = '<style>table{border-collapse:collapse;width:90%;}th,td{border:1px solid black;}</style>';
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdf, false, true);

        $writer->open();
        $writer->write(array('john "2', 'doe', '1'));
        $writer->close();

        $this->assertAttributeContains($borderedStyle, 'html', $writer);
        $this->assertFileExists($this->filename);
    }

    public function testWithoutBorders()
    {
        $borderedStyle = '<style>table{border-collapse:collapse;width:90%;}th,td{border:1px solid black;}</style>';
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdf, false, false);

        $writer->open();
        $writer->write(array('john "2', 'doe', '1'));
        $writer->close();

        $this->assertAttributeNotContains($borderedStyle, 'html', $writer);
        $this->assertFileExists($this->filename);
    }

    public function testWithFooterHtml()
    {
        $options = array(
            'footer-html' => '<footer>CUSTOM FOOTER</footer>',
        );
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdf, false, false, $options);

        $writer->open();
        $writer->write(array('john "2', 'doe', '1'));
        $writer->close();

        $this->assertAttributeContains($options['footer-html'], 'html', $writer);
        $this->assertFileExists($this->filename);
    }

    public function testWithHeaderHtml()
    {
        $options = array(
            'header-html' => '<h1>HEADER HTML</h1>',
        );
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdf, false, false, $options);

        $writer->open();
        $writer->write(array('john "2', 'doe', '1'));
        $writer->close();

        $this->assertAttributeContains($options['header-html'], 'html', $writer);
        $this->assertFileExists($this->filename);
    }
}
