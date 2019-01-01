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
    '\Sonata\\'.__NAMESPACE__.'\XmlWriter',
    __NAMESPACE__.'\XmlWriter'
);

if (false) {
    class XmlWriter extends \Sonata\Exporter\Writer\XmlWriter
    {
    }
}
