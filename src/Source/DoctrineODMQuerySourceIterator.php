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

use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Sonata\Exporter\Exception\InvalidMethodCallException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

final class DoctrineODMQuerySourceIterator implements SourceIteratorInterface
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var IterableResult
     */
    private $iterator;

    /**
     * @var array
     */
    private $propertyPaths = [];

    /**
     * @var PropertyAccess
     */
    private $propertyAccessor;

    /**
     * @var string default DateTime format
     */
    private $dateTimeFormat;

    /**
     * @param Query $query  The Doctrine Query
     * @param array $fields Fields to export
     */
    public function __construct(Query $query, array $fields, string $dateTimeFormat = 'r')
    {
        $this->query = clone $query;

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

        $this->query->getDocumentManager()->getUnitOfWork()->detach($current);

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
            throw new InvalidMethodCallException('Cannot rewind a Doctrine\ODM\Query');
        }

        $this->iterator = $this->query->iterate();
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
