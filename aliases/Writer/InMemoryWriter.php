<?php

namespace Exporter\Writer;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\InMemoryWriter',
    __NAMESPACE__.'\InMemoryWriter'
);

if (false) {
    class InMemoryWriter extends \Sonata\Exporter\Writer\InMemoryWriter
    {
    }
}
