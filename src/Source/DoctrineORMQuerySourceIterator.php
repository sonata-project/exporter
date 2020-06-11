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
use Sonata\Exporter\Exception\InvalidMethodCallException;
use Sonata\Exporter\Field\ExportField;

/**
 * Class DoctrineORMQuerySourceIterator
 *
 * @final since sonata/exporter 2.x
 */
class DoctrineORMQuerySourceIterator extends AbstractPropertySourceIterator implements SourceIteratorInterface
{
    /**
     * @var Query
     */
    protected $query;

    /**
     * @param array<string|ExportField> $fields Fields to export
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

    final public function rewind(): void
    {
        if ($this->iterator) {
            throw new InvalidMethodCallException('Cannot rewind a Doctrine\ORM\Query');
        }

        $this->iterator = $this->query->iterate();
        $this->iterator->rewind();
    }
}
