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
    private string $filename;

    private string $delimiter;

    private string $enclosure;

    private string $escape;

    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $file;

    private bool $showHeaders;

    private int $position;

    private bool $withBom;

    private string $terminate;

    /**
     * @throws \RuntimeException
     */
    public function __construct(
        string $filename,
        string $delimiter = ',',
        string $enclosure = '"',
        string $escape = '\\',
        bool $showHeaders = true,
        bool $withBom = false,
        string $terminate = "\n"
    ) {
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->showHeaders = $showHeaders;
        $this->terminate = $terminate;
        $this->position = 0;
        $this->withBom = $withBom;

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
        $this->file = fopen($this->filename, 'w', false);
        if ("\n" !== $this->terminate) {
            stream_filter_register('filterTerminate', CsvWriterTerminate::class);
            stream_filter_append($this->file, 'filterTerminate', \STREAM_FILTER_WRITE, ['terminate' => $this->terminate]);
        }
        if (true === $this->withBom) {
            fprintf($this->file, \chr(0xEF).\chr(0xBB).\chr(0xBF));
        }
    }

    public function close(): void
    {
        fclose($this->file);
    }

    public function write(array $data): void
    {
        if (0 === $this->position && $this->showHeaders) {
            fputcsv($this->file, array_keys($data), $this->delimiter, $this->enclosure, $this->escape);

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

        $result = @fputcsv($this->file, $data, $this->delimiter, $this->enclosure, $this->escape);

        if (!$result) {
            throw new InvalidDataFormatException();
        }

        ++$this->position;
    }
}
