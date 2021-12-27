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
use Sonata\Exporter\Source\CsvSourceIterator;

final class CsvSourceIteratorTest extends TestCase
{
    /**
     * @var string
     */
    protected $filename;

    protected function setUp(): void
    {
        $this->filename = 'foobar.csv';

        if (is_file($this->filename)) {
            unlink($this->filename);
        }

        $csv = <<<'EOF'
firstname,name
John 1,Doe
John 2,Doe
"John, 3", Doe
EOF;
        file_put_contents($this->filename, $csv);
    }

    protected function tearDown(): void
    {
        unlink($this->filename);
    }

    public function testHandler(): void
    {
        $iterator = new CsvSourceIterator($this->filename);

        $i = 0;
        foreach ($iterator as $value) {
            static::assertIsArray($value);
            static::assertCount(2, $value);
            static::assertSame($i, $iterator->key());
            $keys = array_keys($value);
            static::assertSame('firstname', $keys[0]);
            static::assertSame('name', $keys[1]);
            ++$i;
        }
        static::assertSame(3, $i);
    }

    public function testNoHeaders(): void
    {
        $iterator = new CsvSourceIterator($this->filename, ',', '"', '\\', false);

        $i = 0;
        foreach ($iterator as $value) {
            static::assertIsArray($value);
            static::assertCount(2, $value);
            static::assertSame($i, $iterator->key());
            ++$i;
        }
        static::assertSame(4, $i);
    }

    public function testRewind(): void
    {
        $iterator = new CsvSourceIterator($this->filename);

        $i = 0;
        foreach ($iterator as $value) {
            static::assertIsArray($value);
            static::assertCount(2, $value);
            ++$i;
        }
        static::assertSame(3, $i);

        $i = 0;
        foreach ($iterator as $value) {
            static::assertIsArray($value);
            static::assertCount(2, $value);
            ++$i;
        }
        static::assertSame(3, $i);
    }
}
