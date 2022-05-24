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

namespace Sonata\Exporter\Bridge\Symfony;

@trigger_error(sprintf(
    'The %s\SonataExporterSymfonyBundle class is deprecated since sonata-project/exporter 2.12, to be removed in version 3.0. Use %s instead.',
    __NAMESPACE__,
    SonataExporterBundle::class
), \E_USER_DEPRECATED);

class_alias(SonataExporterBundle::class, __NAMESPACE__.'\SonataExporterSymfonyBundle');
