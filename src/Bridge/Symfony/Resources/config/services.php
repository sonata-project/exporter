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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sonata\Exporter\Exporter;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\JsonWriter;
use Sonata\Exporter\Writer\XlsWriter;
use Sonata\Exporter\Writer\XlsxWriter;
use Sonata\Exporter\Writer\XmlWriter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // Use "param" function for creating references to parameters when dropping support for Symfony 5.1
    $services = $containerConfigurator->services();

    $services->set('sonata.exporter.writer.csv', CsvWriter::class)
        ->args([
            '%sonata.exporter.writer.csv.filename%',
            '%sonata.exporter.writer.csv.delimiter%',
            '%sonata.exporter.writer.csv.enclosure%',
            '%sonata.exporter.writer.csv.escape%',
            '%sonata.exporter.writer.csv.show_headers%',
            '%sonata.exporter.writer.csv.with_bom%',
        ]);

    $services->set('sonata.exporter.writer.json', JsonWriter::class)
        ->args([
            '%sonata.exporter.writer.json.filename%',
        ]);

    $services->set('sonata.exporter.writer.xls', XlsWriter::class)
        ->args([
            '%sonata.exporter.writer.xls.filename%',
            '%sonata.exporter.writer.xls.show_headers%',
        ]);

    if (class_exists(Spreadsheet::class)) {
        $services->set('sonata.exporter.writer.xlsx', XlsxWriter::class)
            ->args([
                '%sonata.exporter.writer.xlsx.filename%',
                '%sonata.exporter.writer.xlsx.show_headers%',
                '%sonata.exporter.writer.xlsx.show_filters%',
            ]);
    }

    $services->set('sonata.exporter.writer.xml', XmlWriter::class)
        ->args([
            '%sonata.exporter.writer.xml.filename%',
            '%sonata.exporter.writer.xml.main_element%',
            '%sonata.exporter.writer.xml.child_element%',
        ]);

    $services->set('sonata.exporter.exporter', Exporter::class)
        ->public();

    $services->alias(Exporter::class, 'sonata.exporter.exporter');
};
