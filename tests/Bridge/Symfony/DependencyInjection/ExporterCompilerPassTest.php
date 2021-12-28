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

namespace Sonata\Exporter\Tests\Bridge\Symfony\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sonata\Exporter\Bridge\Symfony\DependencyInjection\Compiler\ExporterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class ExporterCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testWritersAreAddedToTheExporter(): void
    {
        $exporter = new Definition();
        $this->setDefinition('sonata.exporter.exporter', $exporter);

        $writer = new Definition();
        $writer->addTag('sonata.exporter.writer');
        $this->setDefinition('foo_writer', $writer);

        $this->compile();
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sonata.exporter.exporter',
            'addWriter',
            [new Reference('foo_writer')]
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ExporterCompilerPass());
    }
}
