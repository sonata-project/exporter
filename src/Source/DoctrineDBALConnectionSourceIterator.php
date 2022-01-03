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
 * @phpstan-implements SourceIteratorInterface<mixed, array>
 */
final class DoctrineDBALConnectionSourceIterator implements SourceIteratorInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $query;

    /**
     * @var mixed[]
     */
    private $parameters;

    /**
     * @var array<string, mixed>|false
     */
    private $current;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var Result
     */
    private $result;

    /**
     * @param mixed[] $parameters
     */
    public function __construct(Connection $connection, string $query, array $parameters = [])
    {
        $this->connection = $connection;
        $this->query = $query;
        $this->parameters = $parameters;
    }

    /**
     * @return array<string, mixed>
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        \assert(\is_array($this->current));

        return $this->current;
    }

    public function next(): void
    {
        $this->current = $this->result->fetchAssociative();
        ++$this->position;
    }

    /**
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function key()
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
