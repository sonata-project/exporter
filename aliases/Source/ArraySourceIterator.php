<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\ArraySourceIterator',
    __NAMESPACE__.'\ArraySourceIterator'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    class ArraySourceIterator extends \Sonata\Exporter\Source\ArraySourceIterator
    {
    }
}
