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
     * @var bool
     */
    protected $showHeaders;

    /**
     * @var int
     */
    protected $row;

    /**
     * @var int
     */
    protected $cell;
    /**
     * @var \PHPExcel
     */
    protected $objPHPExcel;

    /**
     * @var PHPExcel_Writer_IWriter
     */
    protected $objWriter;

    /**
     * @throws \RuntimeException
     *
     * @param      $filename
     * @param bool $showHeaders
     */
    public function __construct($filename, $showHeaders = true)
    {
        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->row = 1;
        $this->cell = 0;

        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function getDefaultMimeType()
    {
        return 'application/vnd.ms-excel';
    }

    /**
     * {@inheritdoc}
     */
    final public function getFormat()
    {
        return 'xls';
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $this->objPHPExcel = new \PHPExcel();

        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->objWriter->save('php://output');
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $data)
    {
        $this->init($data);

        foreach ($data as $value) {
            $this->objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($this->cell).$this->row, $value);
            ++$this->cell;
        }
        $this->cell = 0;
        ++$this->row;
    }

    /**
     * @param $data
     *
     * @return array mixed
     */
    protected function init($data)
    {
        if ($this->showHeaders) {
            foreach ($data as $header => $value) {
                $this->objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($this->cell).'1', $header);
                $this->objPHPExcel->getActiveSheet()->getStyle($this->getNameFromNumber($this->cell).'1')->getFont()->setBold(true);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension($this->getNameFromNumber($this->cell))->setAutoSize(true);
                ++$this->cell;
            }
            $this->showHeaders = false;
            ++$this->row;
        }
        $this->cell = 0;
    }

    public function getNameFromNumber($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1).$letter;
        }

        return $letter;
    }
}
