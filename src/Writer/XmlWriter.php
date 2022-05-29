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
use Sonata\Exporter\Exception\RuntimeException;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
final class XmlWriter implements TypedWriterInterface
{
    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $file;

    /**
     * @throws \RuntimeException
     */
    public function __construct(
        private string $filename,
        private string $mainElement = 'datas',
        private string $childElement = 'data'
    ) {
        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }
    }

    public function getDefaultMimeType(): string
    {
        return 'text/xml';
    }

    public function getFormat(): string
    {
        return 'xml';
    }

    public function open(): void
    {
        $file = fopen($this->filename, 'w', false);
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }

        $this->file = $file;
        fwrite($this->file, sprintf("<?xml version=\"1.0\" ?>\n<%s>\n", $this->mainElement));
    }

    /**
     * @psalm-suppress InvalidPassByReference
     *
     * @see https://github.com/vimeo/psalm/issues/7505
     */
    public function close(): void
    {
        fwrite($this->getFile(), sprintf('</%s>', $this->mainElement));
        fclose($this->getFile());
    }

    public function write(array $data): void
    {
        fwrite($this->getFile(), sprintf("<%s>\n", $this->childElement));

        foreach ($data as $k => $v) {
            $this->generateNode($k, $v);
        }

        fwrite($this->getFile(), sprintf("</%s>\n", $this->childElement));
    }

    /**
     * @throws \RuntimeException
     */
    private function generateNode(string $name, mixed $value): void
    {
        if (\is_array($value)) {
            throw new RuntimeException('Not implemented');
        } elseif (\is_scalar($value) || null === $value) {
            fwrite($this->getFile(), sprintf("<%s><![CDATA[%s]]></%s>\n", $name, (string) $value, $name));
        } else {
            throw new InvalidDataFormatException('Invalid data');
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
