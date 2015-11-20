<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Writer;

use Knp\Snappy\Pdf;

/**
 * Export data in PDF format.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
final class KnpSnappyWriter implements WriterInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $wkhtmltopdf;

    /**
     * @var bool
     */
    private $showHeaders;

    /**
     * @var bool
     */
    private $showBorders;

    /**
     * @var array
     *
     * @see http://wkhtmltopdf.org/usage/wkhtmltopdf.txt
     */
    private $snappyOptions;

    /**
     * @var resource
     */
    private $file;

    /**
     * @var string
     */
    private $html = '
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name=ProgId content=Pdf.Document>
    <meta name=Generator content="https://github.com/sonata-project/exporter">
    <style></style>
  </head>
  <body>
    [header-html]
    <table>[content]</table>
    [footer-html]
  </body>
</html>';

    /**
     * @throws \RuntimeException
     *
     * @param string $filename
     * @param bool   $showHeaders
     * @param bool   $showBorders
     * @param array  $snappyOptions KnpSnappy options (wkhtmltopdf)
     * @param string $wkhtmltopdf   Path of the wkhtmltopdf binary
     */
    public function __construct(
        $filename,
        $wkhtmltopdf,
        $showHeaders = true,
        $showBorders = true,
        array $snappyOptions = array()
    ) {
        if (!is_bool($showHeaders)) {
            throw new \InvalidArgumentException(sprintf('The second argument of "%s::__construct()" must be of type boolean, %s given', get_class($this), gettype($showHeaders)));
        }

        if (!is_bool($showBorders)) {
            throw new \InvalidArgumentException(sprintf('The third argument of "%s::__construct()" must be of type boolean, %s given', get_class($this), gettype($showBorders)));
        }

        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file "%s" already exists', $filename));
        }

        if (!is_executable($wkhtmltopdf)) {
            throw new \RuntimeException('The file "%s" doesn\'t exist or is not executable', $wkhtmltopdf);
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->showBorders = $showBorders;
        $this->snappyOptions = $snappyOptions;
        $this->wkhtmltopdf = $wkhtmltopdf;
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
    public function write(array $data)
    {
        $tmpFile = tmpfile();

        if ($this->showBorders) {
            $this->addBorders();
        }

        $this->addMarkupFromOptions();

        if ($this->showHeaders) {
            fwrite($tmpFile, $this->getHeaders(array_keys($data)));
        }

        fwrite($tmpFile, '<tr>');

        foreach ($data as $value) {
            fwrite($tmpFile, sprintf('<td>%s</td>', $value));
        }

        fwrite($tmpFile, '</tr>');

        $tmpFileMetaData = stream_get_meta_data($tmpFile);
        $tmpFilePath = $tmpFileMetaData['uri'];

        fwrite($this->file, $this->generatePdf($tmpFilePath));

        fclose($tmpFile);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        fclose($this->file);
    }

    /**
     * Generates a PDF using KnpSnappy.
     *
     * @param string $htmlFilename.
     *
     * @return string The generated PDF content.
     */
    private function generatePdf($htmlFilename)
    {
        $snappy = new Pdf();
        $snappy->setBinary($this->wkhtmltopdf);

        foreach ($this->snappyOptions as $key => $value) {
            $snappy->setOption($key, $value);
        }

        $this->html = str_replace('[content]', file_get_contents($htmlFilename), $this->html);

        return $snappy->getOutputFromHtml($this->html);
    }

    /**
     * Add style for bordered table.
     */
    private function addBorders()
    {
        $borderedStyle = '<style>table{border-collapse:collapse;width:90%;}th,td{border:1px solid black;}</style>';

        $this->html = str_replace('<style></style>', $borderedStyle, $this->html);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getHeaders(array $columnNames)
    {
        $row = '';

        foreach ($columnNames as $name) {
            $row .= sprintf('<th>%s</th>', $name);
        }

        return sprintf('<tr>%s</tr>', $row);
    }

    /**
     * Handles header-html/footer-html wkhtmltopdf options.
     */
    private function addMarkupFromOptions()
    {
        $markupOptions = array('header-html', 'footer-html');

        foreach ($markupOptions as $key) {
            if (!isset($this->snappyOptions[$key])) {
                $this->snappyOptions[$key] = '';
            }

            $this->html = str_replace(
                sprintf('[%s]', $key),
                $this->snappyOptions[$key],
                $this->html
            );

            unset($this->snappyOptions[$key]);
        }
    }
}
