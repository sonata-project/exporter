<?php

namespace Exporter\Writer;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\WriterInterface',
    __NAMESPACE__.'\WriterInterface'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    interface WriterInterface extends Exporter\Writer
    {
    }
}
