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

namespace Sonata\Exporter\Tests\Bridge\Symfony\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sonata\Exporter\Bridge\Symfony\DependencyInjection\SonataExporterExtension;

final class SonataExporterExtensionTest extends AbstractExtensionTestCase
{
    public function testExporterServiceIsPresent(): void
    {
        $this->load();
        $this->assertContainerBuilderHasService('sonata.exporter.exporter');
    }

    public function testServiceParametersArePresent(): void
    {
        $this->load();
        foreach ([
            'sonata.exporter.writer.csv.filename',
            'sonata.exporter.writer.csv.delimiter',
            'sonata.exporter.writer.csv.enclosure',
            'sonata.exporter.writer.csv.escape',
            'sonata.exporter.writer.csv.show_headers',
            'sonata.exporter.writer.csv.with_bom',
            'sonata.exporter.writer.json.filename',
            'sonata.exporter.writer.xls.filename',
            'sonata.exporter.writer.xls.show_headers',
            'sonata.exporter.writer.xml.filename',
            'sonata.exporter.writer.xml.main_element',
            'sonata.exporter.writer.xml.child_element',
        ] as $parameter) {
            $this->assertContainerBuilderHasParameter($parameter);
        }
    }

    protected function getContainerExtensions(): array
    {
        return [
            new SonataExporterExtension(),
        ];
    }
}
