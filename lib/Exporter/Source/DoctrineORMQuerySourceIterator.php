<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Source;

use Exporter\Exception\InvalidMethodCallException;
use Doctrine\ORM\Query;
use Exporter\Source\SourceIteratorInterface;
use Symfony\Component\Form\Util\PropertyPath;

class DoctrineORMQuerySourceIterator implements SourceIteratorInterface
{
    /**
     * @var \Doctrine\ORM\Query
     */
    protected $query;

    /**
     * @var \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    protected $iterator;

    protected $propertyPaths;

    /**
     * @param \Doctrine\ORM\Query $query  The Doctrine Query
     * @param array               $fields Fields to export
     */
    public function __construct(Query $query, array $fields)
    {
        $this->query = clone $query;
        $this->query->setParameters($query->getParameters());

        $this->propertyPaths = array();
        foreach ($fields as $name => $field) {
            if (is_string($name) && is_string($field)) {
                $this->propertyPaths[$name] = new PropertyPath($field);
            } else {
                $this->propertyPaths[$field] = new PropertyPath($field);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $current = $this->iterator->current();

        $data = array();

        foreach ($this->propertyPaths as $name => $propertyPath) {
            $data[$name] = $this->getValue($propertyPath->getValue($current[0]));
        }

        $this->query->getEntityManager()->getUnitOfWork()->detach($current[0]);

        return $data;
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function getValue($value)
    {
        if (is_array($value) or $value instanceof \Traversable) {
            $value = null;
        } elseif ($value instanceof \DateTime) {
            $value = $value->format('r');
        } elseif (is_object($value)) {
            $value = (string) $value;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if ($this->iterator) {
            throw new InvalidMethodCallException('Cannot rewind a Doctrine\ORM\Query');
        }

        $this->iterator = $this->query->iterate();
        $this->iterator->rewind();
    }
}
