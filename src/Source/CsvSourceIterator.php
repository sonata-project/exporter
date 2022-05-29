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

/**
 * Read data from a csv file.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 *
 * @phpstan-implements \Iterator<array<mixed>>
 */
final class CsvSourceIterator implements \Iterator
{
    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $file;

    /**
     * @var array<string>
     */
    private array $columns = [];

    private int $position = 0;

    /**
     * @var array<string>|false
     */
    private array|false $currentLine = [];

    public function __construct(
        private string $filename,
        private string $delimiter = ',',
        private string $enclosure = '"',
        private string $escape = '\\',
        private bool $hasHeaders = true
    ) {
    }

    /**
     * @return array<string|null>
     */
    public function current(): array
    {
        \assert(\is_array($this->currentLine));

        return $this->currentLine;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        \assert(\is_resource($this->file));

        $line = fgetcsv($this->file, 0, $this->delimiter, $this->enclosure, $this->escape);
        $this->currentLine = $line;
        ++$this->position;
        if ($this->hasHeaders && \is_array($line)) {
            $data = [];
            foreach ($line as $key => $value) {
                $data[$this->columns[$key]] = $value;
            }
            $this->currentLine = $data;
        }
    }

    public function rewind(): void
    {
        $file = fopen($this->filename, 'r');
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }
        $this->file = $file;

        $this->position = 0;
        $line = fgetcsv($this->file, 0, $this->delimiter, $this->enclosure, $this->escape);
        if ($this->hasHeaders && \is_array($line)) {
            $this->columns = $line;
            $line = fgetcsv($this->file, 0, $this->delimiter, $this->enclosure, $this->escape);
        }
        $this->currentLine = $line;
        if ($this->hasHeaders && \is_array($line)) {
            $data = [];
            foreach ($line as $key => $value) {
                $data[$this->columns[$key]] = $value;
            }
            $this->currentLine = $data;
        }
    }

    public function valid(): bool
    {
        if (!\is_array($this->currentLine)) {
            if (\is_resource($this->file)) {
                fclose($this->file);
            }

            return false;
        }

        return true;
    }
}
