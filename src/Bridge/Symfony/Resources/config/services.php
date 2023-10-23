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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sonata\Exporter\Exporter;
use Sonata\Exporter\ExporterInterface;
use Sonata\Exporter\Formatter\BoolFormatter;
use Sonata\Exporter\Formatter\DateIntervalFormatter;
use Sonata\Exporter\Formatter\DateTimeFormatter;
use Sonata\Exporter\Formatter\EnumFormatter;
use Sonata\Exporter\Formatter\IterableFormatter;
use Sonata\Exporter\Formatter\StringableFormatter;
use Sonata\Exporter\Formatter\SymfonyTranslationFormatter;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\JsonWriter;
use Sonata\Exporter\Writer\XlsWriter;
use Sonata\Exporter\Writer\XlsxWriter;
use Sonata\Exporter\Writer\XmlWriter;
use Symfony\Contracts\Translation\TranslatorInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set('sonata.exporter.writer.csv', CsvWriter::class)
        ->args([
            param('sonata.exporter.writer.csv.filename'),
            param('sonata.exporter.writer.csv.delimiter'),
            param('sonata.exporter.writer.csv.enclosure'),
            param('sonata.exporter.writer.csv.escape'),
            param('sonata.exporter.writer.csv.show_headers'),
            param('sonata.exporter.writer.csv.with_bom'),
        ]);

    $services->set('sonata.exporter.writer.json', JsonWriter::class)
        ->args([
            param('sonata.exporter.writer.json.filename'),
        ]);

    $services->set('sonata.exporter.writer.xls', XlsWriter::class)
        ->args([
            param('sonata.exporter.writer.xls.filename'),
            param('sonata.exporter.writer.xls.show_headers'),
        ]);

    if (class_exists(Spreadsheet::class)) {
        $services->set('sonata.exporter.writer.xlsx', XlsxWriter::class)
            ->args([
                param('sonata.exporter.writer.xlsx.filename'),
                param('sonata.exporter.writer.xlsx.show_headers'),
                param('sonata.exporter.writer.xlsx.show_filters'),
            ]);
    }

    $services->set('sonata.exporter.writer.xml', XmlWriter::class)
        ->args([
            param('sonata.exporter.writer.xml.filename'),
            param('sonata.exporter.writer.xml.main_element'),
            param('sonata.exporter.writer.xml.child_element'),
        ]);

    $services->set('sonata.exporter.exporter', Exporter::class)
        ->public();

    $services->alias(Exporter::class, 'sonata.exporter.exporter');
    $services->alias(ExporterInterface::class, 'sonata.exporter.exporter');

    $services->set('sonata.exporter.formatter.bool', BoolFormatter::class)
        ->tag('sonata.exporter.formatter');
    $services->set('sonata.exporter.formatter.dateinterval', DateIntervalFormatter::class)
        ->tag('sonata.exporter.formatter');
    $services->set('sonata.exporter.formatter.datetime', DateTimeFormatter::class)
        ->tag('sonata.exporter.formatter');
    $services->set('sonata.exporter.formatter.enum', EnumFormatter::class)
        ->tag('sonata.exporter.formatter');
    $services->set('sonata.exporter.formatter.iterable', IterableFormatter::class)
        ->tag('sonata.exporter.formatter');
    $services->set('sonata.exporter.formatter.stringable', StringableFormatter::class)
        ->tag('sonata.exporter.formatter');

    if (interface_exists(TranslatorInterface::class)) {
        $services->set('sonata.exporter.formatter.symfony_translator', SymfonyTranslationFormatter::class)
            ->tag('sonata.exporter.formatter');
    }
};
