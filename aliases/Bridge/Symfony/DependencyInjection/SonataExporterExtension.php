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
    '\Sonata\\'.__NAMESPACE__.'\SonataExporterExtension',
    __NAMESPACE__.'\SonataExporterExtension'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    final class SonataExporterExtension extends \Sonata\Exporter\Bridge\Symfony\DependencyInjection\SonataExporterExtension
    {
    }
}
