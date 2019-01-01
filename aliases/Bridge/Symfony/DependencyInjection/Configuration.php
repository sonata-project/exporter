<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Bridge\Symfony\DependencyInjection;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\Configuration',
    __NAMESPACE__.'\Configuration'
);

if (false) {
    final class Configuration extends \Sonata\Exporter\Bridge\Symfony\DependencyInjection\Configuration
    {
    }
}
