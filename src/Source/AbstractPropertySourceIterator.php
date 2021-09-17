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

use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

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
     * @var \Iterator|null
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
     * @var string[]
     */
    protected $fields = [];

    /**
     * @param string[] $fields Fields to export
     */
    public function __construct(array $fields, string $dateTimeFormat = 'r')
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->fields = $fields;

        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        $current = $this->iterator->current();

        return $this->getCurrentData($current);
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    abstract public function rewind(): void;

    public function setDateTimeFormat(string $dateTimeFormat): void
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
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

    /**
     * @param object|mixed[] $current
     */
    protected function getCurrentData($current): array
    {
        $data = [];
        foreach ($this->fields as $key => $field) {
            if (\is_string($field)) {
                $name = \is_string($key) ? $key : $field;
                $propertyPath = $field;
            } else {
                throw new \TypeError('Unsupported field type. Field should be a string.');
            }

            try {
                $propertyValue = $this->propertyAccessor->getValue($current, new PropertyPath($propertyPath));

                $data[$name] = $this->getValue($propertyValue);
            } catch (UnexpectedTypeException $e) {
                // Non existent object in path will be ignored but a wrong path will still throw exceptions
                $data[$name] = null;
            }
        }

        return $data;
    }

    /**
     * @param mixed $value
     *
     * @return bool|int|float|string|null
     */
    protected function getValue($value)
    {
        switch (true) {
            case \is_array($value):
                return '['.implode(', ', array_map([$this, 'getValue'], $value)).']';
            case $value instanceof \Traversable:
                return '['.implode(', ', array_map([$this, 'getValue'], iterator_to_array($value))).']';
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
}
