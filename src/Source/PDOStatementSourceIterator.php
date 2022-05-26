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

use Sonata\Exporter\Exception\InvalidMethodCallException;

/**
 * @phpstan-implements \Iterator<array<mixed>>
 */
final class PDOStatementSourceIterator implements \Iterator
{
    private mixed $current = null;

    private int $position = 0;

    private bool $rewinded = false;

    public function __construct(private \PDOStatement $statement)
    {
    }

    /**
     * @return array<mixed>
     */
    public function current(): array
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->current = $this->statement->fetch(\PDO::FETCH_ASSOC);
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return \is_array($this->current);
    }

    public function rewind(): void
    {
        if ($this->rewinded) {
            throw new InvalidMethodCallException('Cannot rewind a PDOStatement');
        }

        $this->current = $this->statement->fetch(\PDO::FETCH_ASSOC);
        $this->rewinded = true;
    }
}
