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

namespace Sonata\Exporter\Writer;

use Sonata\Exporter\Exception\InvalidDataFormatException;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
final class CsvWriter implements TypedWriterInterface
{
    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $file;

    private int $position;

    /**
     * @throws \RuntimeException
     */
    public function __construct(
        private string $filename,
        private string $delimiter = ',',
        private string $enclosure = '"',
        private string $escape = '\\',
        private bool $showHeaders = true,
        private bool $withBom = false,
        private string $terminate = "\n"
    ) {
        $this->position = 0;

        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }
    }

    public function getDefaultMimeType(): string
    {
        return 'text/csv';
    }

    public function getFormat(): string
    {
        return 'csv';
    }

    public function open(): void
    {
        $file = fopen($this->filename, 'w', false);
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }
        $this->file = $file;

        if ("\n" !== $this->terminate) {
            stream_filter_register('filterTerminate', CsvWriterTerminate::class);
            stream_filter_append($this->file, 'filterTerminate', \STREAM_FILTER_WRITE, ['terminate' => $this->terminate]);
        }
        if (true === $this->withBom) {
            fprintf($this->getFile(), \chr(0xEF).\chr(0xBB).\chr(0xBF));
        }
    }

    /**
     * @psalm-suppress InvalidPassByReference
     *
     * @see https://github.com/vimeo/psalm/issues/7505
     */
    public function close(): void
    {
        fclose($this->getFile());
    }

    public function write(array $data): void
    {
        if (0 === $this->position && $this->showHeaders) {
            fputcsv($this->getFile(), array_keys($data), $this->delimiter, $this->enclosure, $this->escape);

            ++$this->position;
        }

        if (1 !== \strlen($this->delimiter) ||
            1 !== \strlen($this->enclosure) ||
            1 !== \strlen($this->escape)) {
            throw new InvalidDataFormatException(<<<'EXCEPTION'
            Context: trying to write CSV data
            Problem: delimiter, enclosure or escape character is actually a
            string longer than just one character
            Solution: pick an actual character
            EXCEPTION);
        }

        $result = @fputcsv($this->getFile(), $data, $this->delimiter, $this->enclosure, $this->escape);

        if (false === $result) {
            throw new InvalidDataFormatException();
        }

        ++$this->position;
    }

    /**
     * @return resource
     */
    private function getFile()
    {
        if (!\is_resource($this->file)) {
            throw new \LogicException('You MUST open the file first');
        }

        return $this->file;
    }
}
