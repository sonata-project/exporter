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
final class JsonWriter implements TypedWriterInterface
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
    public function __construct(private string $filename)
    {
        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }
    }

    public function getDefaultMimeType(): string
    {
        return 'application/json';
    }

    public function getFormat(): string
    {
        return 'json';
    }

    public function open(): void
    {
        $file = fopen($this->filename, 'w', false);
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }

        $this->file = $file;
        fwrite($this->file, '[');
    }

    /**
     * @psalm-suppress InvalidPassByReference
     *
     * @see https://github.com/vimeo/psalm/issues/7505
     */
    public function close(): void
    {
        fwrite($this->getFile(), ']');
        fclose($this->getFile());
    }

    public function write(array $data): void
    {
        fwrite($this->getFile(), ($this->position > 0 ? ',' : '').json_encode($data, \JSON_THROW_ON_ERROR));

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
