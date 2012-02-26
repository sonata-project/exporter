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
    private $filename;

    private $delimiter;

    private $enclosure;

    private $escape;

    private $file;

    /**
     * @param $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($filename, $delimiter = ",", $enclosure = "\"", $escape = "\\" )
    {
        $this->filename  = $filename;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;

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
        fwrite($this->file, $this->prepareData($data));
    }

    /**
     * @param array $data
     * @return string
     */
    private function prepareData(array $data)
    {
        foreach ($data as $pos => $value) {
            $data[$pos] = sprintf("%s%s%s", $this->enclosure, $this->escape($value), $this->enclosure);
        }

        return join($this->delimiter, $data)."\r\n";
    }

    /**
     * @param $value
     * @return string
     */
    private function escape($value)
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