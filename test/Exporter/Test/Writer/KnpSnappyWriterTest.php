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
        $pdfGenerator = $this->prophesize('Knp\Snappy\Pdf');
        $writer = new KnpSnappyWriter(
            $pdfGenerator->reveal(),
            vfsStream::url('outputDirectory/output.pdf'),
            '/path/to/wkhtmlpdf'
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
