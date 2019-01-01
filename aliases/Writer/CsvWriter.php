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
    '\Sonata\\'.__NAMESPACE__.'\CsvWriter',
    __NAMESPACE__.'\CsvWriter'
);

if (false) {
    class CsvWriter extends \Sonata\Exporter\Writer\CsvWriter
    {
    }
}
