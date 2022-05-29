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
 * Read data from a Xml file.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 *
 * @phpstan-implements \Iterator<array<mixed>>
 */
abstract class AbstractXmlSourceIterator implements \Iterator
{
    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    protected $file;

    /**
     * @var string[]
     */
    protected array $columns = [];

    protected ?\XMLParser $parser = null;

    protected int $currentRowIndex = 0;

    protected int $currentColumnIndex = 0;

    /**
     * @var array<mixed>|null
     */
    protected ?array $currentRow = null;

    /**
     * @var array<string, array<string>>
     */
    protected $bufferedRow = [];

    protected bool $currentRowEnded = false;

    protected int $position = 0;

    public function __construct(
        protected string $filename,
        protected bool $hasHeaders = true
    ) {
    }

    /**
     * Start element handler.
     *
     * @param array<string, string> $attributes
     */
    abstract public function tagStart(\XMLParser $parser, string $name, array $attributes = []): void;

    /**
     * End element handler.
     */
    abstract public function tagEnd(\XMLParser $parser, string $name): void;

    /**
     * Tag content handler.
     */
    abstract public function tagContent(\XMLParser $parser, string $data): void;

    /**
     * @return array<mixed>
     */
    final public function current(): array
    {
        \assert(\is_array($this->currentRow));

        return $this->currentRow;
    }

    final public function key(): mixed
    {
        return $this->position;
    }

    final public function next(): void
    {
        $this->parseRow();
        $this->prepareCurrentRow();
        ++$this->position;
    }

    final public function rewind(): void
    {
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, [$this, 'tagStart'], [$this, 'tagEnd']);
        xml_set_character_data_handler($this->parser, [$this, 'tagContent']);
        xml_parser_set_option($this->parser, \XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($this->parser, \XML_OPTION_SKIP_WHITE, 0);

        $file = fopen($this->filename, 'r');
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }
        $this->file = $file;

        $this->bufferedRow = [];
        $this->currentRowIndex = 0;
        $this->currentColumnIndex = 0;
        $this->position = 0;
        $this->parseRow();
        if ($this->hasHeaders) {
            $this->columns = array_shift($this->bufferedRow) ?? [];
            $this->parseRow();
        }
        $this->prepareCurrentRow();
    }

    final public function valid(): bool
    {
        \assert(null !== $this->parser);
        \assert(\is_resource($this->file));

        if (!\is_array($this->currentRow)) {
            xml_parser_free($this->parser);
            fclose($this->file);

            return false;
        }

        return true;
    }

    /**
     * Parse until </Row> reached.
     */
    final protected function parseRow(): void
    {
        if (null === $this->parser) {
            throw new \LogicException('A parser MUST be set to parse a row.');
        }

        // only parse the next row if only one in buffer
        if (\count($this->bufferedRow) > 1) {
            return;
        }
        if (!\is_resource($this->file) || feof($this->file)) {
            $this->currentRow = null;

            return;
        }

        $this->currentRowEnded = false;

        // read file until row is ended
        // @phpstan-ignore-next-line: The currentRowEnded value is updated when parsing the data
        while (!$this->currentRowEnded && !feof($this->file)) {
            $data = fread($this->file, 1024);
            if (false === $data) {
                throw new \RuntimeException('Cannot read the ressource');
            }

            xml_parse($this->parser, $data);
        }
    }

    /**
     * Prepare the row to return.
     */
    protected function prepareCurrentRow(): void
    {
        $this->currentRow = array_shift($this->bufferedRow);
        if (\is_array($this->currentRow)) {
            $datas = [];
            foreach ($this->currentRow as $key => $value) {
                if ($this->hasHeaders) {
                    $datas[$this->columns[$key]] = html_entity_decode($value);
                } else {
                    $datas[$key] = html_entity_decode($value);
                }
            }
            $this->currentRow = $datas;
        }
    }
}
