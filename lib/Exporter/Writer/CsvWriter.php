<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Writer;

use Exporter\Exception\InvalidDataFormatException;

class CsvWriter implements WriterInterface
{
    protected $filename;

    protected $delimiter;

    protected $enclosure;

    protected $escape;

    protected $file;

    protected $showHeaders;

    protected $position;

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param bool   $showHeaders
     */
    public function __construct($filename, $delimiter = ",", $enclosure = "\"", $escape = "\\", $showHeaders = true)
    {
        $this->filename    = $filename;
        $this->delimiter   = $delimiter;
        $this->enclosure   = $enclosure;
        $this->escape      = $escape;
        $this->showHeaders = $showHeaders;
        $this->position    = 0;

        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $this->file = fopen($this->filename, 'w', false);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        fclose($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data)
    {
        if ($this->position == 0 && $this->showHeaders) {
            $this->addHeaders($data);

            $this->position++;
        }

        fwrite($this->file, $this->prepareData($data));

        $this->position++;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function addHeaders(array $data)
    {
        $headers = array();
        foreach ($data as $header => $value) {
            $headers[] = $header;
        }

        fwrite($this->file, join($this->delimiter, $headers) . "\r\n");
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function prepareData(array $data)
    {
        foreach ($data as $pos => $value) {
            $data[$pos] = sprintf("%s%s%s", $this->enclosure, $this->escape($value), $this->enclosure);
        }

        return join($this->delimiter, $data) . "\r\n";
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function escape($value)
    {
        if (strlen($this->enclosure) == 0) {
            if (mb_strpos($value, '"')) {
                throw new InvalidDataFormatException('The data must be delimeted by a valid enclosure');
            }

            return $value;
        }

        $value = mb_ereg_replace(
            sprintf('(%s)', $this->enclosure),
            sprintf('%s\1', $this->enclosure),
            $value
        );

        $value = mb_ereg_replace(
            sprintf('(%s)', $this->delimiter),
            sprintf('%s\1', $this->escape),
            $value
        );

        return trim($value);
    }
}
