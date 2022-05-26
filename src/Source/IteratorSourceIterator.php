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

namespace Sonata\Exporter\Source;

/**
 * SourceIterator implementation based on Iterator.
 *
 * @phpstan-implements \Iterator<array<mixed>>
 */
class IteratorSourceIterator implements \Iterator
{
    /**
     * @param \Iterator<mixed, array<mixed>> $iterator Iterator with string array elements
     */
    public function __construct(protected \Iterator $iterator)
    {
    }

    final public function getIterator(): \Iterator
    {
        return $this->iterator;
    }

    /**
     * @return array<mixed>
     */
    public function current(): array
    {
        return $this->iterator->current();
    }

    final public function next(): void
    {
        $this->iterator->next();
    }

    final public function key(): mixed
    {
        return $this->iterator->key();
    }

    final public function valid(): bool
    {
        return $this->iterator->valid();
    }

    final public function rewind(): void
    {
        $this->iterator->rewind();
    }
}
