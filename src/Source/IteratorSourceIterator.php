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
 */
class IteratorSourceIterator implements SourceIteratorInterface
{
    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @param \Iterator $iterator Iterator with string array elements
     */
    public function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return $this->iterator;
    }

    public function current()
    {
        return $this->iterator->current();
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }
}
