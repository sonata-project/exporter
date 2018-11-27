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

namespace Sonata\Exporter\Source;

use PropelCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * Read data from a PropelCollection.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
final class PropelCollectionSourceIterator implements SourceIteratorInterface
{
    /**
     * @var \PropelCollection
     */
    private $collection;

    /**
     * @var \ArrayIterator
     */
    private $iterator;

    /**
     * @var array
     */
    private $propertyPaths = [];

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var string default DateTime format
     */
    private $dateTimeFormat;

    /**
     * @param array $fields Fields to export
     */
    public function __construct(PropelCollection $collection, array $fields, string $dateTimeFormat = 'r')
    {
        $this->collection = clone $collection;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($fields as $name => $field) {
            if (\is_string($name) && \is_string($field)) {
                $this->propertyPaths[$name] = new PropertyPath($field);
            } else {
                $this->propertyPaths[$field] = new PropertyPath($field);
            }
        }
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function current()
    {
        $current = $this->iterator->current();

        $data = [];

        foreach ($this->propertyPaths as $name => $propertyPath) {
            $data[$name] = $this->getValue($this->propertyAccessor->getValue($current, $propertyPath));
        }

        return $data;
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        if ($this->iterator) {
            $this->iterator->rewind();

            return;
        }

        $this->iterator = $this->collection->getIterator();
        $this->iterator->rewind();
    }

    public function setDateTimeFormat(string $dateTimeFormat): void
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @return mixed
     */
    private function getValue($value)
    {
        if (\is_array($value) || $value instanceof \Traversable) {
            $value = null;
        } elseif ($value instanceof \DateTimeInterface) {
            $value = $value->format($this->dateTimeFormat);
        } elseif (\is_object($value)) {
            $value = (string) $value;
        }

        return $value;
    }
}
