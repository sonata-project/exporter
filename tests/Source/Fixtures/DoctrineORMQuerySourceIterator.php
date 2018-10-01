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

namespace Sonata\Exporter\Test\Source\Fixtures;

use Sonata\Exporter\Source\DoctrineORMQuerySourceIterator as BaseDoctrineORMQuerySourceIterator;

/**
 * @author Joseph Maarek <josephmaarek@gmail.com>
 */
final class DoctrineORMQuerySourceIterator extends BaseDoctrineORMQuerySourceIterator
{
    public function getValue($value)
    {
        return parent::getValue($value);
    }
}
