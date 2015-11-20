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

use Knp\Snappy\Pdf;

/**
 * Export data in Pdf format.
 *
 * Full list of available options
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class KnpSnappyWriter implements WriterInterface
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $wktmltopdfBinary;

    /**
     * @var bool
     */
    protected $showHeaders;

    /**
     * @var bool
     */
    protected $showBorders;

    /**
     * @var array
     */
    protected $snappyOptions;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var @resource
     */
    protected $tmpFile;

    /**
     * @throws \RuntimeException
     *
     * @param string $filename
     * @param bool   $showHeaders
     * @param bool   $showBorders
     * @param array  $snappyOptions     Pdf options
     * @param string $wkhtmltopdfBinary Path of the wkhtmltopdf binary
     */
    public function __construct($filename, $wkhtmltopdfBinary, $showHeaders = true, $showBorders = true, array $snappyOptions = null)
    {
        $this->filename          = $filename;
        $this->showHeaders       = $showHeaders;
        $this->showBorders       = $showBorders;
        $this->snappyOptions     = $snappyOptions;
        $this->wkhtmltopdfBinary = $wkhtmltopdfBinary;
        $this->position          = 0;

        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }

        if (!is_file($wkhtmltopdfBinary)) {
            throw new \RuntimeException('Unable to find wkhtmltopdf binary');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $this->tmpFile = tmpfile();
        fwrite($this->tmpFile, '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name=ProgId content=Pdf.Document><meta name=Generator content="https://github.com/sonata-project/exporter"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" crossorigin="anonymous"></head><body>');

        if (isset($this->snappyOptions['header-html'])) {
            fwrite($this->tmpFile, $this->snappyOptions['header-html']);
            unset($this->snappyOptions['header-html']);
        }
        fwrite($this->tmpFile, '<table class="table ');
        if (true === $this->showBorders) {
            fwrite($this->tmpFile, 'table-bordered');
        }

        fwrite($this->tmpFile, '" >');
    }

    /**
     * @param $data
     *
     * @return array mixed
     */
    protected function init($data)
    {
        if ($this->position > 0) {
            return;
        }

        if (true === $this->showHeaders) {
            fwrite($this->tmpFile, '<tr>');
            foreach ($data as $header => $value) {
                fwrite($this->tmpFile, sprintf('<th>%s</th>', $header));
            }
            fwrite($this->tmpFile, '</tr>');
            ++$this->position;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data)
    {
        $this->init($data);

        fwrite($this->tmpFile, '<tr>');
        foreach ($data as $value) {
            fwrite($this->tmpFile, sprintf('<td>%s</td>', $value));
        }
        fwrite($this->tmpFile, '</tr>');

        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $tmpFileMetaData = stream_get_meta_data($this->tmpFile);
        $tmpFilePath = $tmpFileMetaData['uri'];
        fwrite($this->tmpFile, '</table>');
        if (isset($this->snappyOptions['footer-html'])) {
            fwrite($this->tmpFile, $this->snappyOptions['footer-html']);
            unset($this->snappyOptions['footer-html']);
        }
        fwrite($this->tmpFile, '</body></html>');

        $this->generate($tmpFilePath);
        fclose($this->tmpFile);
    }

    /**
     * Generates a Pdf document.
     */
    protected function generate($tmpFilePath)
    {
        $snappy = new Pdf();
        $snappy->setBinary($this->wkhtmltopdfBinary);

        if (!is_null($this->snappyOptions)) {
            foreach ($this->snappyOptions as $key => $value) {
                $snappy->setOption($key, $value);
            }
        }

        $html = file_get_contents($tmpFilePath);
        $output = fopen($this->filename, 'w', false);
        fwrite($output, $snappy->getOutputFromHtml($html));
        fclose($output);
    }
}
