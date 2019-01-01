<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
