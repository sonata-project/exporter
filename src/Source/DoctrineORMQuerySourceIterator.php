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

use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\Query;
use Sonata\Exporter\Exception\InvalidMethodCallException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

class DoctrineORMQuerySourceIterator implements SourceIteratorInterface
{
    private const DATE_PARTS = [
        'y' => 'Y',
        'm' => 'M',
        'd' => 'D',
    ];
    private const TIME_PARTS = [
        'h' => 'H',
        'i' => 'M',
        's' => 'S',
    ];

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var IterableResult
     */
    protected $iterator;

    /**
     * @var array
     */
    protected $propertyPaths = [];

    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * @var string default DateTime format
     */
    protected $dateTimeFormat;

    /**
     * @param Query $query  The Doctrine Query
     * @param array $fields Fields to export
     */
    public function __construct(Query $query, array $fields, string $dateTimeFormat = 'r')
    {
        $this->query = clone $query;
        $this->query->setParameters($query->getParameters());
        foreach ($query->getHints() as $name => $value) {
            $this->query->setHint($name, $value);
        }

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

    final public function current()
    {
        $current = $this->iterator->current();

        $data = [];

        foreach ($this->propertyPaths as $name => $propertyPath) {
            try {
                $data[$name] = $this->getValue($this->propertyAccessor->getValue($current[0], $propertyPath));
            } catch (UnexpectedTypeException $e) {
                //non existent object in path will be ignored
                $data[$name] = null;
            }
        }

        $this->query->getEntityManager()->getUnitOfWork()->detach($current[0]);

        return $data;
    }

    final public function next(): void
    {
        $this->iterator->next();
    }

    final public function key()
    {
        return $this->iterator->key();
    }

    final public function valid(): bool
    {
        return $this->iterator->valid();
    }

    final public function rewind(): void
    {
        if ($this->iterator) {
            throw new InvalidMethodCallException('Cannot rewind a Doctrine\ORM\Query');
        }

        $this->iterator = $this->query->iterate();
        $this->iterator->rewind();
    }

    final public function setDateTimeFormat(string $dateTimeFormat): void
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    final public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @return string An ISO8601 duration
     */
    public function getDuration(\DateInterval $interval): string
    {
        $datePart = '';
        foreach (self::DATE_PARTS as $datePartAttribute => $datePartAttributeString) {
            if ($interval->$datePartAttribute !== 0) {
                $datePart .= $interval->$datePartAttribute.$datePartAttributeString;
            }
        }

        $timePart = '';
        foreach (self::TIME_PARTS as $timePartAttribute => $timePartAttributeString) {
            if ($interval->$timePartAttribute !== 0) {
                $timePart .= $interval->$timePartAttribute.$timePartAttributeString;
            }
        }

        if ('' === $datePart && '' === $timePart) {
            return 'P0Y';
        }

        return 'P'.$datePart.('' !== $timePart ? 'T'.$timePart : '');
    }

    /**
     * @return string|null
     */
    protected function getValue($value)
    {
        if (\is_array($value) || $value instanceof \Traversable) {
            $value = null;
        } elseif ($value instanceof \DateTimeInterface) {
            $value = $value->format($this->dateTimeFormat);
        } elseif ($value instanceof \DateInterval) {
            $value = $this->getDuration($value);
        } elseif (\is_object($value)) {
            $value = (string) $value;
        }

        return $value;
    }
}
