<?php

namespace Exporter\Writer;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\CsvWriter',
    __NAMESPACE__.'\CsvWriter'
);

use Exporter\Exception\InvalidDataFormatException;

if (false) {
    class CsvWriter extends \Sonata\Exporter\Writer\CsvWriter
    {
    }
}
