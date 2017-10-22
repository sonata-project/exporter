<?php

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
class XlsWriter implements TypedWriterInterface
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var resource
     */
    protected $file;

    /**
     * @var bool
     */
    protected $showHeaders;

    /**
     * @var int
     */
    protected $position;

    /**
     * @throws \RuntimeException
     *
     * @param mixed $filename
     * @param bool  $showHeaders
     */
    public function __construct($filename, bool $showHeaders = true)
    {
        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->position = 0;

        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exists', $filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function getDefaultMimeType(): string
    {
        return 'application/vnd.ms-excel';
    }

    /**
     * {@inheritdoc}
     */
    final public function getFormat(): string
    {
        return 'xls';
    }

    /**
     * {@inheritdoc}
     */
    public function open(): void
    {
        $this->file = fopen($this->filename, 'w', false);
        fwrite($this->file, '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name=ProgId content=Excel.Sheet><meta name=Generator content="https://github.com/sonata-project/exporter"></head><body><table>');
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        fwrite($this->file, '</table></body></html>');
        fclose($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data): void
    {
        $this->init($data);

        fwrite($this->file, '<tr>');
        foreach ($data as $value) {
            fwrite($this->file, sprintf('<td>%s</td>', $value));
        }
        fwrite($this->file, '</tr>');

        ++$this->position;
    }

    /**
     * @param $data
     */
    protected function init($data)
    {
        if ($this->position > 0) {
            return;
        }

        if ($this->showHeaders) {
            fwrite($this->file, '<tr>');
            foreach ($data as $header => $value) {
                fwrite($this->file, sprintf('<th>%s</th>', $header));
            }
            fwrite($this->file, '</tr>');
            ++$this->position;
        }
    }
}
