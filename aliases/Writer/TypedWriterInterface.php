<?php

namespace Exporter\Writer;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\TypedWriterInterface',
    __NAMESPACE__.'\TypedWriterInterface'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    interface TypedWriterInterface extends \Sonata\Exporter\Writer\TypedWriterInterface
    {
    }
}
