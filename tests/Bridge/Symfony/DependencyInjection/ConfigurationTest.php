<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Tests\Bridge\Symfony\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use Sonata\Exporter\Bridge\Symfony\DependencyInjection\Configuration;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    public function getConfiguration()
    {
        return new Configuration();
    }

    public function testDefault()
    {
        $this->assertProcessedConfigurationEquals(array(
            array(),
        ), array(
            'exporter' => array('default_writers' => array(
                'csv', 'json', 'xls', 'xml',
            )),
            'writers' => array(
                'csv' => array(
                    'filename' => 'php://output',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\',
                    'show_headers' => true,
                    'with_bom' => false,
                ),
                'json' => array(
                    'filename' => 'php://output',
                ),
                'xls' => array(
                    'filename' => 'php://output',
                    'show_headers' => true,
                ),
                'xml' => array(
                    'filename' => 'php://output',
                    'show_headers' => true,
                    'main_element' => 'datas',
                    'child_element' => 'data',
                ),
            ),
        ));
    }
}
