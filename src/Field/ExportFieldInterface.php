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

interface ExportFieldInterface
{
    public function __construct(string $label, string $path, array $options = []);

    public function getLabel(): string;

    public function getPath(): string;

    public function getOptions(): array;

    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException when the $value type is not supported
     */
    public function formatValue($value): ?string;
}
