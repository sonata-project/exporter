<?php

namespace Exporter\Test;

use Exporter\Handler;
use Exporter\Source\ArraySourceIterator;
use Exporter\Writer\InMemoryWriter;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandler()
    {
        $source = $this->getMock('Exporter\Source\SourceIteratorInterface');
        $writer = $this->getMock('Exporter\Writer\WriterInterface');
        $writer->expects($this->once())->method('open');
        $writer->expects($this->once())->method('close');

        $exporter = new Handler($source, $writer);
        $exporter->export();
    }

    public function testHandlerWithTransformer()
    {
        $source = new ArraySourceIterator(array(array(0), array(1), array(2)));
        $writer = new InMemoryWriter();

        $exporter = new Handler($source, $writer);
        $exporter->export(function ($data) {
            return array((1 << $data[0]));
        });

        $this->assertEquals(array(array(1), array(2), array(4)), $writer->getElements());
    }
}
