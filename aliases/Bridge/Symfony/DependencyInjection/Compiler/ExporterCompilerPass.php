<?php

namespace Exporter\Bridge\Symfony\DependencyInjection\Compiler;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\ExporterCompilerPass',
    __NAMESPACE__.'\ExporterCompilerPass'
);

if (false) {
    /**
     * @deprecated since version 1.x, to be removed in 2.0.
     */
    final class ExporterCompilerPass extends \Sonata\Exporter\Bridge\Symfony\DependencyInjection\Compiler\ExporterCompilerPass
    {
    }
}
