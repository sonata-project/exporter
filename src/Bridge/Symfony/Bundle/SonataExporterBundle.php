<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Bridge\Symfony\Bundle;

use Sonata\Exporter\Bridge\Symfony\SonataExporterBundle as ForwardCompatibleSonataExporterBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

@trigger_error(sprintf(
    'The %s\SonataExporterBundle class is deprecated since sonata-project/exporter 2.4, to be removed in version 3.0. Use %s instead.',
    __NAMESPACE__,
    ForwardCompatibleSonataExporterBundle::class
), \E_USER_DEPRECATED);

if (false) {
    /**
     * NEXT_MAJOR: remove this class.
     *
     * @deprecated since sonata-project/exporter 2.4, to be removed in version 3.0. Use Sonata\Exporter\Bridge\Symfony\SonataExporterBundle instead.
     */
    final class SonataExporterBundle extends Bundle
    {
    }
}

class_alias(ForwardCompatibleSonataExporterBundle::class, __NAMESPACE__.'\SonataExporterBundle');
