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

/**
 * @phpstan-implements \Iterator<array<mixed>>
 */
abstract class AbstractPropertySourceIterator implements \Iterator
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

    protected ?\Iterator $iterator = null;

    protected PropertyAccessor $propertyAccessor;

    /**
     * @param string[] $fields Fields to export
     */
    public function __construct(
        protected array $fields,
        protected string $dateTimeFormat = 'r'
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return array<string, mixed>
     */
    public function current(): array
    {
        $current = $this->getIterator()->current();

        return $this->getCurrentData($current);
    }

    public function next(): void
    {
        $this->getIterator()->next();
    }

    public function key(): mixed
    {
        return $this->getIterator()->key();
    }

    public function valid(): bool
    {
        return $this->getIterator()->valid();
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

    protected function getIterator(): \Iterator
    {
        if (null === $this->iterator) {
            throw new \LogicException('The iterator MUST be set in the "rewind()" method.');
        }

        return $this->iterator;
    }

    /**
     * @phpstan-param object|array<mixed> $current TODO: Change to param when https://github.com/rectorphp/rector/issues/7186 is released
     *
     * @return array<string, mixed>
     */
    protected function getCurrentData(object|array $current): array
    {
        $data = [];
        foreach ($this->fields as $key => $field) {
            $name = \is_string($key) ? $key : $field;
            $propertyPath = $field;

            try {
                $propertyValue = $this->propertyAccessor->getValue($current, new PropertyPath($propertyPath));

                $data[$name] = $this->getValue($propertyValue);
            } catch (UnexpectedTypeException) {
                // Non existent object in path will be ignored but a wrong path will still throw exceptions
                $data[$name] = null;
            }
        }

        return $data;
    }

    protected function getValue(mixed $value): bool|int|float|string|null
    {
        return match (true) {
            \is_array($value) => '['.implode(', ', array_map([$this, 'getValue'], $value)).']',
            $value instanceof \Traversable => '['.implode(', ', array_map([$this, 'getValue'], iterator_to_array($value))).']',
            $value instanceof \DateTimeInterface => $value->format($this->dateTimeFormat),
            $value instanceof \DateInterval => $this->getDuration($value),
            \is_object($value) => method_exists($value, '__toString') ? (string) $value : null,
            default => $value,
        };
    }

    /**
     * @return string An ISO8601 duration
     */
    private function getDuration(\DateInterval $interval): string
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
