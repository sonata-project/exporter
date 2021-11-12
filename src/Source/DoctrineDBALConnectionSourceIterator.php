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
     * @var array
     */
    private $parameters;

    /**
     * @var mixed
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

    public function __construct(Connection $connection, string $query, array $parameters = [])
    {
        $this->connection = $connection;
        $this->query = $query;
        $this->parameters = $parameters;
    }

    public function current()
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->current = $this->result->fetchAssociative();
        ++$this->position;
    }

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

        if (method_exists($statement, 'executeQuery')) {
            $this->result = $statement->executeQuery($this->parameters);
        } else {
            $statement->execute($this->parameters);

            $this->result = $statement;
        }

        $this->next();
    }
}
