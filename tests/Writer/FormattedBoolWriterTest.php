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
use Sonata\Exporter\Writer\FormattedBoolWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;

/**
 * Format boolean before use another writer.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
final class FormattedBoolWriterTest extends TestCase
{
    /**
     * @var string
     */
    private $trueLabel;

    /**
     * @var string
     */
    private $falseLabel;

    protected function setUp(): void
    {
        $this->trueLabel = 'yes';
        $this->falseLabel = 'no';
    }

    public function testValidDataFormat(): void
    {
        $data = ['john', 'doe', false, true];
        $expected = ['john', 'doe', 'no', 'yes'];
        $mock = $this->createMock(TypedWriterInterface::class);
        $mock->expects(static::once())
               ->method('write')
               ->with(static::equalTo($expected));
        $writer = new FormattedBoolWriter($mock, $this->trueLabel, $this->falseLabel);
        $writer->open();
        $writer->write($data);
        $writer->close();
    }
}
