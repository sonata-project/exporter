<?php

namespace Exporter\Exception;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\InvalidMethodCallException',
    __NAMESPACE__.'\InvalidMethodCallException'
);

if (false) {
    class InvalidMethodCallException extends \Sonata\Exporter\Exception\InvalidMethodCallException
    {
    }
}
