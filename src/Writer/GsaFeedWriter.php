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
 * Generates a GSA feed.
 *
 * @author Rémi Marseille <marseille@ekino.com>
 */
final class GsaFeedWriter implements WriterInterface
{
    public const LIMIT_SIZE = 31_457_280;

    private int $bufferPart;

    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $buffer;

    private int $bufferSize;

    /**
     * @param \SplFileInfo $folder     The folder to store the generated feed(s)
     * @param string       $dtd        A DTD URL (something like http://gsa.example.com/gsafeed.dtd)
     * @param string       $datasource A datasouce
     * @param string       $feedtype   A feedtype (full|incremental|metadata-and-url)
     */
    public function __construct(
        private \SplFileInfo $folder,
        private string $dtd,
        private string $datasource,
        private string $feedtype
    ) {
        $this->bufferPart = 0;
        $this->bufferSize = 0;
    }

    public function open(): void
    {
        $this->generateNewPart();
    }

    public function write(array $data): void
    {
        $line = sprintf(
            "        <record url=\"%s\" mimetype=\"%s\" action=\"%s\"/>\n",
            $data['url'],
            $data['mime_type'],
            $data['action']
        );

        // + 18 corresponding to the length of the closing tags
        if (($this->bufferSize + \strlen($line) + 18) > self::LIMIT_SIZE) {
            $this->generateNewPart();
        }

        $this->bufferSize += fwrite($this->getBuffer(), $line);
    }

    public function close(): void
    {
        if (null !== $this->buffer) {
            $this->closeFeed();
        }
    }

    /**
     * Generates a new file.
     *
     * @throws \RuntimeException
     */
    private function generateNewPart(): void
    {
        if (null !== $this->buffer) {
            $this->closeFeed();
        }

        $this->bufferSize = 0;
        ++$this->bufferPart;

        if (!$this->folder->isWritable()) {
            throw new \RuntimeException(sprintf('Unable to write to folder: %s', (string) $this->folder));
        }

        $filename = sprintf('%s/feed_%05d.xml', (string) $this->folder, $this->bufferPart);
        $buffer = fopen($filename, 'w');
        if (false === $buffer) {
            throw new \Exception(sprintf('Cannot open file %s.', $filename));
        }
        $this->buffer = $buffer;

        $this->bufferSize += fwrite(
            $this->buffer,
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE gsafeed PUBLIC "-//Google//DTD GSA Feeds//EN" "$this->dtd">
<gsafeed>
    <header>
        <datasource>$this->datasource</datasource>
        <feedtype>$this->feedtype</feedtype>
    </header>

    <group>

XML
        );
    }

    /**
     * @psalm-suppress InvalidPassByReference
     *
     * @see https://github.com/vimeo/psalm/issues/7505
     */
    private function closeFeed(): void
    {
        fwrite(
            $this->getBuffer(),
            <<<'EOF'
    </group>
</gsafeed>
EOF
        );

        fclose($this->getBuffer());
    }

    /**
     * @return resource
     */
    private function getBuffer()
    {
        if (!\is_resource($this->buffer)) {
            throw new \LogicException('You MUST open the file first');
        }

        return $this->buffer;
    }
}
