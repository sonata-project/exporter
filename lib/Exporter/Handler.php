<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter;

use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\WriterInterface;

class Handler
{
    protected $source;

    protected $writer;

    /**
     * @param Source\SourceIteratorInterface $source
     * @param Writer\WriterInterface         $writer
     */
    public function __construct(SourceIteratorInterface $source, WriterInterface $writer)
    {
        $this->source = $source;
        $this->writer = $writer;
    }

    /**
     * @param \Closure|null $transformer Altering a data row
     *
     * @return void
     */
    public function export(\Closure $transformer = null)
    {
        $this->writer->open();

        if ($transformer) {
            foreach ($this->source as $data) {
                $this->writer->write($transformer($data));
            }
        } else {
            foreach ($this->source as $data) {
                $this->writer->write($data);
            }
        }

        $this->writer->close();
    }

    /**
     * @static
     *
     * @param Source\SourceIteratorInterface $source
     * @param Writer\WriterInterface         $writer
     *
     * @return Handler
     */
    public static function create(SourceIteratorInterface $source, WriterInterface $writer)
    {
        return new self($source, $writer);
    }
}
