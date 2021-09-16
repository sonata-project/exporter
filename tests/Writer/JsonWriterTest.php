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

use Sonata\Exporter\Test\AbstractTypedWriterTestCase;
use Sonata\Exporter\Writer\JsonWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;

class JsonWriterTest extends AbstractTypedWriterTestCase
{
    protected $filename;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filename = 'foobar.json';

        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    protected function tearDown(): void
    {
        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testWrite(): void
    {
        $writer = new JsonWriter($this->filename);
        $writer->open();

        $writer->write(['john "2', 'doe', '1']);
        $writer->write(['john 3', 'doe', '1']);

        $writer->close();

        $expected = '[["john \"2","doe","1"],["john 3","doe","1"]]';
        $content = file_get_contents($this->filename);

        static::assertSame($expected, $content);

        $expected = [
            ['john "2', 'doe', '1'],
            ['john 3', 'doe', '1'],
        ];

        static::assertSame($expected, json_decode($content, false));
    }

    protected function getWriter(): TypedWriterInterface
    {
        return new JsonWriter('/tmp/whatever.json');
    }
}
