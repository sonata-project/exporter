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

abstract class ExportField implements ExportFieldInterface
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array<string, mixed>
     */
    private $options;

    public function __construct(string $label, string $path, array $options = [])
    {
        $this->label = $label;
        $this->path = $path;
        $this->options = $options;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
