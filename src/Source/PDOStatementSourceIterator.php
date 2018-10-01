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

class PDOStatementSourceIterator implements SourceIteratorInterface
{
    /**
     * @var \PDOStatement
     */
    protected $statement;

    /**
     * @var mixed
     */
    protected $current;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var bool
     */
    protected $rewinded = false;

    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
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
        if ($this->rewinded) {
            throw new InvalidMethodCallException('Cannot rewind a PDOStatement');
        }

        $this->current = $this->statement->fetch(\PDO::FETCH_ASSOC);
        $this->rewinded = true;
    }
}
