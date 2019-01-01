<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Test\Source\Fixtures;

/**
 * @author Joseph Maarek <josephmaarek@gmail.com>
 */
final class DoctrineORMQuerySourceIterator extends \Sonata\Exporter\Source\DoctrineORMQuerySourceIterator
{
    public function getValue($value)
    {
        return parent::getValue($value);
    }
}
