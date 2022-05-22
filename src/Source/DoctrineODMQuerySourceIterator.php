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

use Doctrine\ODM\MongoDB\Query\Query;

final class DoctrineODMQuerySourceIterator extends AbstractPropertySourceIterator
{
    private Query $query;

    private int $batchSize;

    /**
     * @param array<string> $fields Fields to export
     */
    public function __construct(Query $query, array $fields, string $dateTimeFormat = 'r', int $batchSize = 100)
    {
        $this->query = clone $query;

        $this->batchSize = $batchSize;

        parent::__construct($fields, $dateTimeFormat);
    }

    /**
     * @return array<string, mixed>
     */
    public function current()
    {
        $current = $this->iterator->current();

        $data = $this->getCurrentData($current);

        if (0 === ($this->iterator->key() % $this->batchSize)) {
            $this->query->getDocumentManager()->clear();
        }

        return $data;
    }

    public function rewind(): void
    {
        if (null === $this->iterator) {
            $this->iterator = $this->query->getIterator();
        }

        $this->iterator->rewind();
    }
}
