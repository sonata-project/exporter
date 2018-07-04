<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Formatter;

use Symfony\Component\PropertyAccess\PropertyPath;

final class ArrayFormatter implements DataFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function supports($data)
    {
        return (is_array($data) || $data instanceof \Traversable);
    }

    /**
     * @inheritDoc
     */
    public function format($data, PropertyPath $propertyPath)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 10;
    }
}
