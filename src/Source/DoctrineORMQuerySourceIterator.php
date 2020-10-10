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

use Doctrine\ORM\Query;

/**
 * @final since sonata-project/exporter 2.4.
 */
class DoctrineORMQuerySourceIterator extends AbstractPropertySourceIterator implements SourceIteratorInterface
{
    /**
     * @var Query
     */
    protected $query;

    /**
     * @param array<string> $fields Fields to export
     */
    public function __construct(Query $query, array $fields, string $dateTimeFormat = 'r')
    {
        $this->query = clone $query;
        $this->query->setParameters($query->getParameters());
        foreach ($query->getHints() as $name => $value) {
            $this->query->setHint($name, $value);
        }

        parent::__construct($fields, $dateTimeFormat);
    }

    public function current()
    {
        $current = $this->iterator->current();

        $data = $this->getCurrentData($current[0]);

        $this->query->getEntityManager()->clear();

        return $data;
    }

    final public function rewind(): void
    {
        $this->iterator = $this->query->iterate();
        $this->iterator->rewind();
    }
}
