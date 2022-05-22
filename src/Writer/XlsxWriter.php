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

namespace Sonata\Exporter\Writer;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;

/**
 * @author Willem Verspyck <willemverspyck@users.noreply.github.com>
 */
final class XlsxWriter implements TypedWriterInterface
{
    private string $filename;

    private bool $showHeaders;

    private bool $showFilters;

    private ?Spreadsheet $spreadsheet = null;

    private ?Worksheet $worksheet = null;

    private int $position;

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function __construct(string $filename, bool $showHeaders = true, bool $showFilters = true)
    {
        if (!class_exists(Spreadsheet::class)) {
            throw new \LogicException('You need the "phpoffice/spreadsheet" package in order to use the XLSX export.');
        }

        if (is_file($filename)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" already exists.', $filename));
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->showFilters = $showFilters;
        $this->position = 1;
    }

    public function getDefaultMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    public function getFormat(): string
    {
        return 'xlsx';
    }

    public function open(): void
    {
        $this->spreadsheet = new Spreadsheet();

        $this->worksheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * @throws WriterException
     */
    public function close(): void
    {
        if ($this->showHeaders && $this->showFilters) {
            $this->worksheet->setAutoFilter($this->worksheet->calculateWorksheetDimension());
            $this->worksheet->setSelectedCellByColumnAndRow(1, 1);
        }

        $excelWriter = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $excelWriter->save($this->filename);
    }

    public function write(array $data): void
    {
        if (1 === $this->position && $this->showHeaders) {
            $this->addHeaders($data);

            ++$this->position;
        }

        $column = 1;

        foreach ($data as $value) {
            $dataFormat = $this->getDataFormat($value);
            $dataValue = $this->getDataValue($value);

            if (null !== $dataFormat) {
                $this->worksheet->getStyleByColumnAndRow($column, $this->position)
                    ->getNumberFormat()
                    ->setFormatCode($dataFormat);
            }

            $dataType = $this->getDataType($value);

            $this->worksheet->setCellValueExplicitByColumnAndRow($column, $this->position, $dataValue, $dataType);

            ++$column;
        }

        ++$this->position;
    }

    /**
     * @param array<mixed> $data
     */
    private function addHeaders(array $data): void
    {
        $column = 1;

        foreach (array_keys($data) as $value) {
            $this->worksheet->setCellValueExplicitByColumnAndRow($column, $this->position, $value, DataType::TYPE_STRING);

            ++$column;
        }
    }

    /**
     * Get the type of the Spreadsheet cell.
     *
     * @param mixed $value
     */
    private function getDataType($value): string
    {
        if (null === $value) {
            return DataType::TYPE_NULL;
        }

        if (\is_bool($value)) {
            return DataType::TYPE_BOOL;
        }

        if ($this->getDateTime($value) instanceof \DateTimeInterface) {
            return DataType::TYPE_NUMERIC;
        }

        if (\is_string($value)) {
            return DataType::TYPE_STRING;
        }

        return DataType::TYPE_NUMERIC;
    }

    /**
     * Get the value of the Spreadsheet cell. DateTime fields must be converted in order to work properly.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function getDataValue($value)
    {
        if (null === $value) {
            return null;
        }

        $dateTime = $this->getDateTime($value);

        if ($dateTime instanceof \DateTimeInterface) {
            return Date::PHPToExcel($dateTime);
        }

        return $value;
    }

    /**
     * Get the format of the spreadsheet cell.
     *
     * @param mixed $value
     */
    private function getDataFormat($value): ?string
    {
        $dateTime = $this->getDateTime($value);

        if ($dateTime instanceof \DateTimeInterface) {
            return sprintf('%s hh:mm:ss', NumberFormat::FORMAT_DATE_DDMMYYYY);
        }

        return null;
    }

    /**
     * Check if the field is a DateTime.
     *
     * @param mixed $value
     */
    private function getDateTime($value): ?\DateTimeInterface
    {
        if (\is_string($value)) {
            $dateTime = \DateTime::createFromFormat(\DateTimeInterface::RFC1123, $value);

            if ($dateTime instanceof \DateTimeInterface) {
                return $dateTime;
            }
        }

        return null;
    }
}
