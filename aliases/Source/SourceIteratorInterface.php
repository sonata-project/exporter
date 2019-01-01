<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\SourceIteratorInterface',
    __NAMESPACE__.'\SourceIteratorInterface'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    interface SourceIteratorInterface extends \Sonata\Exporter\Source\SourceIteratorInterface
    {
    }
}
