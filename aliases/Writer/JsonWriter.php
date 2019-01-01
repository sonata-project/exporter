<?php

namespace Exporter\Writer;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\JsonWriter',
    __NAMESPACE__.'\JsonWriter'
);

if (false) {
    class JsonWriter extends \Sonata\Exporter\Writer\JsonWriter
    {
    }
}
