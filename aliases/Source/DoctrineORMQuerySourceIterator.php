<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\DoctrineORMQuerySourceIterator',
    __NAMESPACE__.'\DoctrineORMQuerySourceIterator'
);

if (false) {
    class DoctrineORMQuerySourceIterator extends \Sonata\Exporter\Source\DoctrineORMQuerySourceIterator
    {
    }
}
