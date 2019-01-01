<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\CsvSourceIterator',
    __NAMESPACE__.'\CsvSourceIterator'
);

if (false) {
    class CsvSourceIterator extends \Sonata\Exporter\Source\CsvSourceIterator
    {
    }
}
