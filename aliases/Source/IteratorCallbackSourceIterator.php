<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\IteratorCallbackSourceIterator',
    __NAMESPACE__.'\IteratorCallbackSourceIterator'
);

if (false) {
    class IteratorCallbackSourceIterator extends \Sonata\Exporter\Source\IteratorCallbackSourceIterator
    {
    }
}
