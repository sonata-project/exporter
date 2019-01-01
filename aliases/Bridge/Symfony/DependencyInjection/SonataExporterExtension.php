<?php

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
