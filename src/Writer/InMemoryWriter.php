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

namespace Sonata\Exporter\Writer;

final class InMemoryWriter implements WriterInterface
{
    /**
     * @var array<mixed>
     */
    private array $elements = [];

    public function open(): void
    {
        $this->elements = [];
    }

    public function close(): void
    {
        unset($this->elements);
    }

    public function write(array $data): void
    {
        $this->elements[] = $data;
    }

    /**
     * @return mixed[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }
}
