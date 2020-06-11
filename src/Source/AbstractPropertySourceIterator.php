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
use Sonata\Exporter\Field\ExportFieldInterface;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * Class DoctrineORMQuerySourceIterator
 *
 * @final
 */
abstract class AbstractPropertySourceIterator implements SourceIteratorInterface
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
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * @var string default DateTime format
     */
    protected $dateTimeFormat;

    /**
     * @var array<string|ExportFieldInterface>
     */
    protected $fields = [];

    /**
     * @param array<string|ExportFieldInterface> $fields Fields to export
     */
    public function __construct(array $fields, string $dateTimeFormat = 'r')
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->fields = $fields;

        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function current()
    {
        $current = $this->iterator->current();

        $data = [];

        foreach ($this->fields as $key => $field) {
            if ($field instanceof ExportFieldInterface) {
                $name = $field->getLabel();
                $propertyPath = $field->getPath();
            } elseif (\is_string($field)) {
                $name = \is_string($key) ? $key : $field;
                $propertyPath = $field;
            } else {
                throw new \TypeError(sprintf(
                    'Unsupported field type. Field should be either an %s or a string.',
                    ExportFieldInterface::class
                ));
            }

            try {
                $propertyValue = $this->propertyAccessor->getValue($current[0], new PropertyPath($propertyPath));
            } catch (UnexpectedTypeException $e) {
                // Non existent object in path will be ignored but a wrong path will still throw exceptions
                $data[$name] = null;
            }

            if ($field instanceof ExportFieldInterface) {
                $data[$name] = $field->formatValue($propertyValue);
            } else {
                $data[$name] = $this->getValue($propertyValue);
            }
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

    abstract function rewind(): void;

    public function setDateTimeFormat(string $dateTimeFormat): void
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @param mixed $value
     *
     * @return string|null
     */
    protected function getValue($value)
    {
        switch (true) {
            case \is_array($value):
            case $value instanceof \Traversable:
                return null;
            case $value instanceof \DateTimeInterface:
                return $value->format($this->dateTimeFormat);
            case $value instanceof \DateInterval:
                return $this->getDuration($value);
            case \is_object($value):
                return method_exists($value, '__toString') ? (string) $value : null;
            default:
                return $value;
        }
    }

    /**
     * NEXT_MAJOR: Change the method visibility to private.
     *
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
}
