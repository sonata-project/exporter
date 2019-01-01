<?php

namespace Exporter\Test;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\AbstractTypedWriterTestCase',
    __NAMESPACE__.'\AbstractTypedWriterTestCase'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    abstract class AbstractTypedWriterTestCase extends \Sonata\Exporter\Test\AbstractTypedWriterTestCase
    {
    }
}
