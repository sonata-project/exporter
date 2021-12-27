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
     * @var array<string, mixed>
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
    public function current()
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->current = $this->result->fetchAssociative();
        ++$this->position;
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return \is_array($this->current);
    }

    public function rewind(): void
    {
        $statement = $this->connection->prepare($this->query);

        // TODO: Keep only the else part when dropping support for Doctrine DBAL < 3.1
        if (method_exists($statement, 'executeQuery')) {
            $this->result = $statement->executeQuery($this->parameters);
        } else {
            $this->result = $statement->execute($this->parameters);
        }

        $this->next();
    }
}
