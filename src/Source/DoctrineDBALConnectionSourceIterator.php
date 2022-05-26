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

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Result;

/**
 * @phpstan-implements \Iterator<array<mixed>>
 */
final class DoctrineDBALConnectionSourceIterator implements \Iterator
{
    /**
     * @var array<string, mixed>|false
     */
    private array | false $current = false;

    private int $position = 0;

    /**
     * Add type hint when dropping support for Doctrine DBAL < 3.1.
     *
     * @var Result
     */
    private $result;

    /**
     * @param mixed[] $parameters
     */
    public function __construct(
        private Connection $connection,
        private string $query,
        private array $parameters = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function current(): array
    {
        \assert(\is_array($this->current));

        return $this->current;
    }

    public function next(): void
    {
        $this->current = $this->result->fetchAssociative();
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

    /**
     * @psalm-suppress InvalidPropertyAssignmentValue
     */
    public function rewind(): void
    {
        $statement = $this->connection->prepare($this->query);

        $result = $statement->execute($this->parameters);

        // TODO: Keep only the if part when dropping support for Doctrine DBAL < 3.1
        // @phpstan-ignore-next-line
        if ($result instanceof Result) {
            $this->result = $result;
        } else { // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            $this->result = $statement;
        }

        $this->next();
    }
}
