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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Driver\Result as DriverResult;
use Doctrine\DBAL\Result;

final class DoctrineDBALConnectionSourceIterator implements SourceIteratorInterface
{
    /**
     * @var Connection|DriverConnection
     */
    private $connection;

    private string $query;

    /**
     * @var mixed[]
     */
    private array $parameters;

    /**
     * @var array<string, mixed>|false
     */
    private $current;

    private int $position = 0;

    /**
     * @var Result|DriverResult
     */
    private $result;

    /**
     * @param Connection|DriverConnection $connection
     * @param mixed[]                     $parameters
     */
    public function __construct($connection, string $query, array $parameters = [])
    {
        if (!$connection instanceof Connection) {
            if (!$connection instanceof DriverConnection) {
                throw new \TypeError(sprintf(
                    '%s: Argument 1 is expected to be an instance of %s, got %s.',
                    __METHOD__,
                    Connection::class,
                    \get_class($connection)
                ));
            }

            @trigger_error(sprintf(
                'Passing an instance of %s as argument 1 is deprecated since sonata-project/exporter 2.13'
                .' and will not work in 3.0. You MUST pass an instance of %s instead',
                \get_class($connection),
                Connection::class
            ), \E_USER_DEPRECATED);
        }

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
        if ($result instanceof Result || $result instanceof DriverResult) {
            $this->result = $result;
        } else {
            // @phpstan-ignore-next-line
            $this->result = $statement;
        }

        $this->next();
    }
}
