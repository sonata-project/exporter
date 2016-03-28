<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test\Source;

use Exporter\Writer\KnpSnappyWriter;

/**
 * Tests the KnpSnapyWriter Writer.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class KnpSnappyWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $wkhtmltopdfBinary;

    public function setUp()
    {
        $this->filename = 'foobar.pdf';
        $this->wkhtmltopdfBinary = __DIR__.'/../../../../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64';
        if (!is_file($this->wkhtmltopdfBinary)) {
            $this->markTestSkipped('The wkhtmltopdf binary is not available');
        }

        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testPdfGeneration()
    {
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdfBinary, false);
        $writer->open();
        $writer->write(array('john "2', 'doe', '1'));
        $writer->close();

        $this->assertFileExists($this->filename);
    }

    public function testWithHeaders()
    {
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdfBinary, true, true);
        $writer->open();
        $writer->write(array('firstname' => 'john "2', 'surname' => 'doe', 'year' => '1'));
        $writer->close();

        $this->assertFileExists($this->filename);
    }

    public function testWithPdfOptions()
    {
        $options = array(
            'header-html' => '<h1>HEADER HTML</h1>',
            'footer-html' => '<footer>CUSTOM FOOTER</footer>',
        );
        $writer = new KnpSnappyWriter($this->filename, $this->wkhtmltopdfBinary, false, false, $options);
        $writer->open();
        $writer->write(array('firstname' => 'john "2', 'surname' => 'doe', 'year' => '1'));
        $writer->close();

        $this->assertFileExists($this->filename);
    }

    public function tearDown()
    {
        unlink($this->filename);
    }
}
