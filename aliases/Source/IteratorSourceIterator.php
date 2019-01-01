<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\IteratorSourceIterator',
    __NAMESPACE__.'\IteratorSourceIterator'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    class IteratorSourceIterator extends \Sonata\Exporter\Source\IteratorSourceIterator
    {
    }
}
