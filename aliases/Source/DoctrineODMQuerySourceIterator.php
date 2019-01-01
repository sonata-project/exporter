<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\DoctrineODMQuerySourceIterator',
    __NAMESPACE__.'\DoctrineODMQuerySourceIterator'
);

if (false) {
    class DoctrineODMQuerySourceIterator extends \Sonata\Exporter\Source\DoctrineODMQuerySourceIterator
    {
    }
}
