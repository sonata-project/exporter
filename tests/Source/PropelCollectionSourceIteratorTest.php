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
 *
 * NEXT_MAJOR: Remove this test.
 *
 * @group legacy
 */
final class PropelCollectionSourceIteratorTest extends TestCase
{
    private \PropelCollection $collection;

    protected function setUp(): void
    {
        if (!class_exists(\PropelCollection::class)) {
            static::markTestIncomplete('Propel is not available');
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

        static::assertCount(3, $data);
    }

    public function testFieldsExtraction(): void
    {
        $data = $this->extract($this->collection, ['id' => '[id]', 'name' => '[name]']);

        static::assertSame([
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
            static::assertArrayHasKey('created_at', $row);
            static::assertIsString($row['created_at']);
        }
    }

    /**
     * @param string[] $fields
     *
     * @return array<mixed>
     */
    private function extract(\PropelCollection $collection, array $fields): array
    {
        $iterator = new PropelCollectionSourceIterator($collection, $fields);

        return iterator_to_array($iterator);
    }
}
