<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Use same namespace as system under test to mock native functions

namespace Exporter\Writer;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use Prophecy\Argument;

function is_executable()
{
    return true;
}

/**
 * Tests the KnpSnappyWriter.
 */
class KnpSnappyWriterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('outputDirectory'));
    }

    public function testWrite()
    {
        $binaryPath = '/path/to/wkhtmlpdf';
        $pdfGenerator = $this->prophesize('Knp\Snappy\Pdf');
        $pdfGenerator->setBinary($binaryPath)->shouldBeCalled();
        $pdfGenerator->setOption('my', 'option')->shouldBeCalled();
        $pdfGenerator->getOutputFromHtml(Argument::that(function ($actualOutput) {
            foreach (array(
                '<style>table', // tests addBorders()
                'should be in the header', // tests addMarkupFromOptions()
                '<table>',
                '<th>foo</th>',
                '<td>bar</td>',
                'baz',
                'should be in the footer', // tests addMarkupFromOptions()
                ) as $expected) {
                if (strpos($actualOutput, $expected) === false) {
                    return false;
                }

                return true;
            }
        }))->shouldBeCalled();

        $writer = new KnpSnappyWriter(
            $pdfGenerator->reveal(),
            vfsStream::url('outputDirectory/output.pdf'),
            $binaryPath,
            true,
            true,
            array(
                'header-html' => 'should be in the header',
                'footer-html' => 'should be in the footer',
                'my' => 'option',
            )
        );
        $writer->open();
        $writer->write(array(
            'foo' => 'bar',
            'bar' => 'baz',
        ));
        $writer->close();

        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('output.pdf'));
    }
}
