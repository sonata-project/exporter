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

namespace Sonata\Exporter\Test\Writer;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Writer\FormattedBoolWriter;

/**
 * Format boolean before use another writer.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class FormattedBoolWriterTest extends TestCase
{
    /**
     * @var string
     */
    protected $trueLabel;

    /**
     * @var string
     */
    protected $falseLabel;

    public function setUp(): void
    {
        $this->trueLabel = 'yes';
        $this->falseLabel = 'no';
    }

    public function testValidDataFormat(): void
    {
        $data = ['john', 'doe', false, true];
        $expected = ['john', 'doe', 'no', 'yes'];
        $mock = $this->getMockBuilder('Sonata\Exporter\Writer\XlsWriter')
                       ->setConstructorArgs(['formatedbool.xls', false])
                       ->getMock();
        $mock->expects($this->any())
               ->method('write')
               ->with($this->equalTo($expected));
        $writer = new FormattedBoolWriter($mock, $this->trueLabel, $this->falseLabel);
        $writer->open();
        $writer->write($data);
        $writer->close();
    }
}
