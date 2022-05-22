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

final class ChainSourceIterator implements SourceIteratorInterface
{
    /**
     * @var \ArrayIterator<array-key, \Iterator>
     */
    private \ArrayIterator $sources;

    /**
     * @param array<\Iterator> $sources
     */
    public function __construct(array $sources = [])
    {
        $this->sources = new \ArrayIterator();

        foreach ($sources as $source) {
            $this->addSource($source);
        }
    }

    public function addSource(\Iterator $source): void
    {
        $this->sources->append($source);
    }

    /**
     * @return array<mixed>
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->sources->current()->current();
    }

    public function next(): void
    {
        $this->sources->current()->next();
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->sources->current()->key();
    }

    public function valid(): bool
    {
        if (!$this->sources->valid()) {
            return false;
        }

        while (!$this->sources->current()->valid()) {
            $this->sources->next();

            if (!$this->sources->valid()) {
                return false;
            }

            $this->sources->current()->rewind();
        }

        return true;
    }

    public function rewind(): void
    {
        if ($this->sources->valid()) {
            $this->sources->current()->rewind();
        }
    }
}
