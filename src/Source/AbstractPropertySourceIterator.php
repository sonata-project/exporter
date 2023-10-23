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

use Sonata\Exporter\Formatter\DateTimeFormatter;
use Sonata\Exporter\Formatter\EnumFormatter;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * @phpstan-implements \Iterator<array<mixed>>
 */
abstract class AbstractPropertySourceIterator implements \Iterator
{
    /**
     * NEXT_MAJOR: Remove this constant.
     */
    private const DATE_PARTS = [
        'y' => 'Y',
        'm' => 'M',
        'd' => 'D',
    ];

    /**
     * NEXT_MAJOR: Remove this constant.
     */
    private const TIME_PARTS = [
        'h' => 'H',
        'i' => 'M',
        's' => 'S',
    ];

    protected ?\Iterator $iterator = null;

    protected PropertyAccessor $propertyAccessor;

    /**
     * @deprecated since sonata-project/exporter 3.x.
     */
    protected string $dateTimeFormat = 'r';

    /**
     * @deprecated since sonata-project/exporter 3.x.
     */
    protected bool $useBackedEnumValue = true;

    /**
     * @param string[] $fields Fields to export
     */
    public function __construct(
        protected array $fields,
        ?string $dateTimeFormat = null,
        ?bool $useBackedEnumValue = null,
        /**
         * NEXT_MAJOR: Remove this property.
         */
        private bool $disableSourceFormatters = false
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        if ($this->disableSourceFormatters) {
            return;
        }

        if (null !== $dateTimeFormat) {
            @trigger_error(sprintf(
                'Passing a value as argument 2 in "%s()" is deprecated since sonata-project/exporter 3.x and will be removed in version 4.0,'
                .' use "%s" instead.',
                __METHOD__,
                DateTimeFormatter::class,
            ), \E_USER_DEPRECATED);

            /** @psalm-suppress DeprecatedProperty */
            $this->dateTimeFormat = $dateTimeFormat;
        }

        if (null !== $useBackedEnumValue) {
            @trigger_error(sprintf(
                'Passing a value as argument 3 in "%s()" is deprecated since sonata-project/exporter 3.x and will be removed in version 4.0,'
                .' use "%s" instead.',
                __METHOD__,
                EnumFormatter::class,
            ), \E_USER_DEPRECATED);

            /** @psalm-suppress DeprecatedProperty */
            $this->useBackedEnumValue = $useBackedEnumValue;
        }
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

    /**
     * @deprecated since sonata-project/exporter 3.x.
     *
     * @psalm-suppress DeprecatedProperty
     */
    public function setDateTimeFormat(string $dateTimeFormat): void
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * @deprecated since sonata-project/exporter 3.x.
     *
     * @psalm-suppress DeprecatedProperty
     */
    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @deprecated since sonata-project/exporter 3.x.
     *
     * @psalm-suppress DeprecatedProperty
     */
    public function useBackedEnumValue(bool $useBackedEnumValue): void
    {
        $this->useBackedEnumValue = $useBackedEnumValue;
    }

    /**
     * @deprecated since sonata-project/exporter 3.x.
     *
     * @psalm-suppress DeprecatedProperty
     */
    public function isBackedEnumValueInUse(): bool
    {
        return $this->useBackedEnumValue;
    }

    protected function getIterator(): \Iterator
    {
        if (null === $this->iterator) {
            throw new \LogicException('The iterator MUST be set in the "rewind()" method.');
        }

        return $this->iterator;
    }

    /**
     * @return array<string, mixed>
     *
     * @phpstan-param object|array<mixed> $current TODO: Change to param when https://github.com/rectorphp/rector/issues/7186 is released
     */
    protected function getCurrentData(object|array $current): array
    {
        $data = [];
        foreach ($this->fields as $key => $field) {
            $name = \is_string($key) ? $key : $field;
            $propertyPath = $field;

            try {
                $propertyValue = $this->propertyAccessor->getValue($current, new PropertyPath($propertyPath));

                // NEXT_MAJOR: Remove this condition.
                if (!$this->disableSourceFormatters) {
                    /** @psalm-suppress DeprecatedMethod */
                    $propertyValue = $this->getValue($propertyValue);
                }

                $data[$name] = $propertyValue;
            } catch (UnexpectedTypeException) {
                // Non existent object in path will be ignored but a wrong path will still throw exceptions
                $data[$name] = null;
            }
        }

        return $data;
    }

    /**
     * @deprecated since sonata-project/exporter 3.x.
     *
     * @psalm-suppress DeprecatedMethod, DeprecatedProperty
     */
    protected function getValue(mixed $value): bool|int|float|string|null
    {
        return match (true) {
            \is_array($value) => '['.implode(', ', array_map([$this, 'getValue'], $value)).']',
            $value instanceof \Traversable => '['.implode(', ', array_map([$this, 'getValue'], iterator_to_array($value))).']',
            $value instanceof \DateTimeInterface => $value->format($this->dateTimeFormat),
            $value instanceof \DateInterval => $this->getDuration($value),
            $value instanceof \BackedEnum && $this->useBackedEnumValue => $value->value,
            $value instanceof \UnitEnum => $value->name,
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
