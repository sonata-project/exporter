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

namespace Sonata\Exporter\Field;

use Sonata\Exporter\Exception\InvalidArgumentException;

final class ExportFieldFactory
{
    public function create(string $path, string $type, array $options = [])
    {
        $label = $options['label'] ?? $path;

        if (!class_exists($type)) {
            throw new InvalidArgumentException(sprintf('Class `%s` not found', $type));
        }

        if (!in_array(ExportFieldInterface::class, class_implements($type))) {
            throw new InvalidArgumentException(sprintf(
                'Class `%s` must implement `%s`',
                $type,
                ExportFieldInterface::class
            ));
        }

        return new $type($label, $path, $options);
    }
}
