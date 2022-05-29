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

/**
 * Generate a Xml Excel file.
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
final class XmlExcelWriter implements WriterInterface
{
    /**
     * @var resource|null
     * @phpstan-var resource|null
     * @psalm-var resource|closed-resource|null
     */
    private $file;

    private int $position = 0;

    private string $header = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:x2="http://schemas.microsoft.com/office/excel/2003/xml" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:html="http://www.w3.org/TR/REC-html40" xmlns:c="urn:schemas-microsoft-com:office:component:spreadsheet"><OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office"></OfficeDocumentSettings><ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel"></ExcelWorkbook><Worksheet ss:Name="Sheet 1"><Table>';

    private string $footer = '</Table></Worksheet></Workbook>';

    /**
     * @param mixed $columnsType Define cells type to use
     *                           If string: force all cells to the given type. e.g: 'Number'
     *                           If array: force only given cells. e.g: array('ean'=>'String', 'price'=>'Number')
     *                           If null: will guess the type. 'Number' if value is numeric, 'String' otherwise
     *
     * @throws \RuntimeException
     */
    public function __construct(
        private string $filename,
        private bool $showHeaders = true,
        private mixed $columnsType = null
    ) {
        if (is_file($filename)) {
            throw new \RuntimeException(sprintf('The file %s already exist', $filename));
        }
    }

    public function open(): void
    {
        $file = fopen($this->filename, 'w', false);
        if (false === $file) {
            throw new \Exception(sprintf('Cannot open file %s.', $this->filename));
        }

        $this->file = $file;
        fwrite($this->file, $this->header);
    }

    public function write(array $data): void
    {
        if (0 === $this->position && $this->showHeaders) {
            $header = array_keys($data);
            fwrite($this->getFile(), $this->getXmlString($header));
            ++$this->position;
        }

        fwrite($this->getFile(), $this->getXmlString($data));
        ++$this->position;
    }

    /**
     * @psalm-suppress InvalidPassByReference
     *
     * @see https://github.com/vimeo/psalm/issues/7505
     */
    public function close(): void
    {
        fwrite($this->getFile(), $this->footer);
        fclose($this->getFile());
    }

    /**
     * Prepare and return XML string for MS Excel XML from array.
     *
     * @param string[] $fields
     */
    private function getXmlString(array $fields = []): string
    {
        $xmlData = [];
        $xmlData[] = '<Row>';
        foreach ($fields as $key => $value) {
            $value = htmlspecialchars($value);

            $value = str_replace(["\r\n", "\r", "\n"], '&#10;', $value);
            $dataType = 'String';
            if (0 !== $this->position || !$this->showHeaders) {
                $dataType = $this->getDataType((string) $key, $value);
            }
            $xmlData[] = '<Cell><Data ss:Type="'.$dataType.'">'.$value.'</Data></Cell>';
        }
        $xmlData[] = '</Row>';

        return implode('', $xmlData);
    }

    private function getDataType(string $key, string $value): string
    {
        $dataType = null;
        if (null !== $this->columnsType) {
            if (\is_string($this->columnsType)) {
                $dataType = $this->columnsType;
            } elseif (\is_array($this->columnsType)) {
                if (\array_key_exists($key, $this->columnsType)) {
                    $dataType = $this->columnsType[$key];
                }
            }
        }
        if (null === $dataType) {
            // guess the type
            if (is_numeric($value)) {
                $dataType = 'Number';
            } else {
                $dataType = 'String';
            }
        }

        return $dataType;
    }

    /**
     * @return resource
     */
    private function getFile()
    {
        if (!\is_resource($this->file)) {
            throw new \LogicException('You MUST open the file first');
        }

        return $this->file;
    }
}
