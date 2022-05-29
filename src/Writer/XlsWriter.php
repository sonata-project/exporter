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

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
final class XlsWriter implements TypedWriterInterface
{
    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $file;

    private int $position = 0;

    /**
     * @throws \RuntimeException
     */
    public function __construct(
        private string $filename,
        private bool $showHeaders = true
    ) {
        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exists', $filename));
        }
    }

    public function getDefaultMimeType(): string
    {
        return 'application/vnd.ms-excel';
    }

    public function getFormat(): string
    {
        return 'xls';
    }

    public function open(): void
    {
        $file = fopen($this->filename, 'w', false);
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }

        $this->file = $file;
        fwrite($this->file, '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name=ProgId content=Excel.Sheet><meta name=Generator content="https://github.com/sonata-project/exporter"></head><body><table>');
    }

    /**
     * @psalm-suppress InvalidPassByReference
     *
     * @see https://github.com/vimeo/psalm/issues/7505
     */
    public function close(): void
    {
        fwrite($this->getFile(), '</table></body></html>');
        fclose($this->getFile());
    }

    public function write(array $data): void
    {
        $this->init($data);

        fwrite($this->getFile(), '<tr>');
        foreach ($data as $value) {
            fwrite($this->getFile(), sprintf('<td>%s</td>', $value));
        }
        fwrite($this->getFile(), '</tr>');

        ++$this->position;
    }

    /**
     * @param array<string> $data
     */
    private function init(array $data): void
    {
        if ($this->position > 0) {
            return;
        }

        if ($this->showHeaders) {
            fwrite($this->getFile(), '<tr>');
            foreach ($data as $header => $value) {
                fwrite($this->getFile(), sprintf('<th>%s</th>', (string) $header));
            }
            fwrite($this->getFile(), '</tr>');
            ++$this->position;
        }
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
