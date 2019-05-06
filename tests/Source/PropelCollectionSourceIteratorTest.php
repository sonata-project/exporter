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
use Sonata\Exporter\Source\PropelCollectionSourceIterator;

/**
 * Tests the PropelCollectionSourceIterator class.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class PropelCollectionSourceIteratorTest extends TestCase
{
    protected $collection;

    public function setUp(): void
    {
        if (!class_exists('PropelCollection')) {
            $this->markTestIncomplete('Propel is not available');
        }

        $data = [
            ['id' => 1, 'name' => 'john',   'mail' => 'john@foo.bar', 'created_at' => new \DateTime()],
            ['id' => 2, 'name' => 'john 2', 'mail' => 'john@foo.bar', 'created_at' => new \DateTime()],
            ['id' => 3, 'name' => 'john 3', 'mail' => 'john@foo.bar', 'created_at' => new \DateTime()],
        ];

        $this->collection = new \PropelCollection();
        $this->collection->setData($data);
    }

    public function testIterator(): void
    {
        $data = $this->extract($this->collection, ['id' => '[id]', 'name' => '[name]']);

        $this->assertCount(3, $data);
    }

    public function testFieldsExtraction(): void
    {
        $data = $this->extract($this->collection, ['id' => '[id]', 'name' => '[name]']);

        $this->assertSame([
             [
                'id' => 1,
                'name' => 'john',
            ],
            [
                'id' => 2,
                'name' => 'john 2',
            ],
            [
                'id' => 3,
                'name' => 'john 3',
            ],
        ], $data);
    }

    public function testDateTimeTransformation(): void
    {
        $data = $this->extract($this->collection, ['id' => '[id]', 'created_at' => '[created_at]']);

        foreach ($data as $row) {
            $this->assertArrayHasKey('created_at', $row);
            $this->assertIsString($row['created_at']);
        }
    }

    protected function extract(\PropelCollection $collection, array $fields)
    {
        $iterator = new PropelCollectionSourceIterator($collection, $fields);

        return iterator_to_array($iterator);
    }
}
