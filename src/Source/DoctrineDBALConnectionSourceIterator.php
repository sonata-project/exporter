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
use Doctrine\DBAL\Driver\Statement;
use Sonata\Exporter\Exception\InvalidMethodCallException;

class DoctrineDBALConnectionSourceIterator implements SourceIteratorInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var mixed
     */
    protected $current;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var Statement
     */
    protected $statement;

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
        $this->current = $this->statement->fetch(\PDO::FETCH_ASSOC);
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
        if ($this->statement) {
            throw new InvalidMethodCallException('Cannot rewind a PDOStatement');
        }

        $this->statement = $this->connection->prepare($this->query);
        $this->statement->execute($this->parameters);

        $this->next();
    }
}
