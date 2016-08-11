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
 * .xls writer bridge for library `phpoffice/phpexcel`
 * @see https://github.com/PHPOffice/PHPExcel
 *
 * @author Lukáš Brzák <lukas.brzak@email.cz>
 */
final class XlsExcelWriter implements TypedWriterInterface
{
    /** @var \PHPExcel $excel */
    protected $excel;

    /** @var \PHPExcel_Worksheet $sheet */
    protected $sheet;

    /** @var \PHPExcel_Writer_Excel2007 $writer */
    protected $writer;

    /** @var string $column */
    protected $column = 'A';

    /** @var int $row */
    protected $row = 1;

    /** @var string $file */
    protected $file;

    /** @var boolean $showHeaders */
    protected $showHeaders;


    /**
     * @param string $file
     * @param bool $showHeaders
     *
     * @throws \PHPExcel_Exception
     */
    public function __construct($file = 'php://output', $showHeaders = true)
    {
        $this->file = $file;
        $this->showHeaders = $showHeaders;
        $this->configure();
    }

    /**
     * @return void
     * @throws \PHPExcel_Exception
     */
    protected function configure()
    {
        $this->excel = new \PHPExcel();
        $this->excel->setActiveSheetIndex(0);
        $this->sheet = $this->excel->getActiveSheet();
        $this->column = 'A';
        $this->row = 1;
    }

    /**
     * @return void
     */
    public function open()
    {
        $this->writer = new \PHPExcel_Writer_Excel2007($this->excel);
    }

    /**
     * @param array $data
     *
     * @throws \PHPExcel_Writer_Exception
     */
    public function write(array $data)
    {
        $this->init($data);

        foreach ($data as $header => $value) {
            $this->sheet->setCellValue(
                $this->getCurrentCell(),
                $value
            );
            $this->nextColumn();
        }
        $this->nextRow();
    }

    /**
     * Save the initial row of headers
     *
     * @param array $data
     */
    protected function init(array $data)
    {
        if ($this->row > 1) {
            return;
        }

        if ($this->showHeaders === true) {
            foreach ($data as $header => $value) {
                $this->sheet->setCellValue(
                    $this->getCurrentCell(),
                    $header
                );
                $this->nextColumn();
            }
            $this->nextRow();
        }
    }

    /**
     * Save the file
     *
     * @throws \PHPExcel_Writer_Exception
     */
    public function close()
    {
        $this->writer->save($this->file);
    }

    /**
     * Get coordinates for current Cell A1, B2, AB22 ..
     *
     * @return string
     */
    protected function getCurrentCell()
    {
        return sprintf('%s%s', $this->column, $this->row);
    }

    /**
     * Increment row number
     *
     * @return void
     */
    protected function nextRow()
    {
        ++$this->row;
        $this->resetColumn();
    }

    /**
     * Increment `A`>`B`; `Z`>`AA`; `AA`>`AB`; `AAZ`>`ABA`
     *
     * @return void
     */
    protected function nextColumn()
    {
        ++$this->column;
    }

    /**
     * Reset pointer to the first Excel column A
     *
     * @return void
     */
    protected function resetColumn()
    {
        $this->column = 'A';
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
}
