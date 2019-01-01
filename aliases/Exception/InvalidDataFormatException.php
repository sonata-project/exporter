<?php

namespace Exporter\Exception;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\InvalidDataFormatException',
    __NAMESPACE__.'\InvalidDataFormatException'
);

if (false) {
    class InvalidDataFormatException extends \Sonata\Exporter\Exception\InvalidDataFormatException
    {
    }
}
