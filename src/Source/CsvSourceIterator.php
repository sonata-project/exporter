<?php

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
 * Read data from a csv file.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Sylvain Rascar <sylvain.rascar@ekino.com>
 */
class CsvSourceIterator implements SeekableSourceIteratorInterface
{
    /**
     * @var string
     */
    protected $filename = null;

    /**
     * @var \SplFileObject
     */
    protected $file = null;

    /**
     * @var string|null
     */
    protected $delimiter = null;

    /**
     * @var string|null
     */
    protected $enclosure = null;

    /**
     * @var string|null
     */
    protected $escape = null;

    /**
     * @var bool|null
     */
    protected $hasHeaders = null;

    /**
     * @var array
     */
    protected $lines = array();

    /**
     * @var array
     */
    protected $columns = array();

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $currentLine = array();

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param bool   $hasHeaders
     */
    public function __construct($filename, $delimiter = ',', $enclosure = '"', $escape = '\\', $hasHeaders = true)
    {
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->hasHeaders = $hasHeaders;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->currentLine;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->currentLine = $this->getNextLine();
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->initializeRead();
        $this->currentLine = $this->getNextLine();
    }

    /**
     * {@inheritdoc}
     */
    final public function seek($position)
    {
        if (!($this->file instanceof \SplFileObject)) {
            $this->initializeRead();
        }

        if ($this->hasHeaders) {
            $this->file->seek($position + 1);
            $this->currentLine = $this->combineColumns($this->file->current());
        } else {
            $this->file->seek($position);
            $this->currentLine = $this->file->current();
        }

        $this->position = $position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        if (!is_array($this->currentLine)) {
            return false;
        }

        return true;
    }

    /**
     * Initialize read process setting CSV options
     * and setting field names. (Assuming that first line is column name).
     */
    private function initializeRead()
    {
        // close the previous file
        if ($this->file instanceof \SplFileObject) {
            $this->file = null;
        }

        $this->file = new \SplFileObject($this->filename);
        $this->file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );
        $this->file->setCsvControl($this->delimiter, $this->enclosure, $this->escape);
        $this->position = 0;

        if ($this->hasHeaders) {
            $this->columns = $this->file->fgetcsv();
        }
    }

    /**
     * @return array|void
     */
    private function getNextLine()
    {
        $line = $this->file->fgetcsv();

        if (false === $line) {
            throw new \RuntimeException(sprintf('An error occurred while reading the csv %s.', $this->file->getRealPath()));
        }

        return $this->combineColumns($line);
    }

    /**
     * @param array $line
     *
     * @return array|void
     */
    private function combineColumns($line)
    {
        if (!$this->hasHeaders) {
            return $line;
        }

        if ($line === array(null) || $line === null) {
            $this->currentLine = null;

            return;
        }

        if (count($this->columns) !== count($line)) {
            throw new \RuntimeException(sprintf('Invalid column count at line %s. Expected %d; Got %d.',
                $this->position,
                count($this->columns),
                count($line)
            ));
        }

        return array_combine($this->columns, $line);
    }
}
