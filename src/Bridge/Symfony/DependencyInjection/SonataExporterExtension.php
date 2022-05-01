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

namespace Sonata\Exporter\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Grégoire Paris <postmaster@greg0ire.fr>
 */
final class SonataExporterExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        $this->configureExporter($container, $config['exporter']);
        $this->configureWriters($container, $config['writers']);
    }

    /**
     * @param array<string, array<string>> $config
     */
    private function configureExporter(ContainerBuilder $container, array $config): void
    {
        foreach (['csv', 'json', 'xls', 'xlsx', 'xml'] as $format) {
            if (\in_array($format, $config['default_writers'], true)) {
                $container->getDefinition('sonata.exporter.writer.'.$format)->addTag(
                    'sonata.exporter.writer'
                );
            }
        }
    }

    /**
     * @param array<string, array<string, mixed>> $config
     */
    private function configureWriters(ContainerBuilder $container, array $config): void
    {
        foreach ($config as $format => $settings) {
            foreach ($settings as $key => $value) {
                $container->setParameter(sprintf(
                    'sonata.exporter.writer.%s.%s',
                    $format,
                    $key
                ), $value);
            }
        }
    }
}
