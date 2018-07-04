<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Source;

use Doctrine\ORM\Query;
use Exporter\Exception\InvalidMethodCallException;
use Exporter\Formatter\ArrayFormatter;
use Exporter\Formatter\DataFormatterInterface;
use Exporter\Formatter\DateTimeFormatter;
use Exporter\Formatter\ObjectToStringFormatter;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

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

    /**
     * @var array
     */
    protected $propertyPaths;

    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * @var string $dateTimeFormat
     */
    protected $dateTimeFormat;

    /**
     * @var DataFormatterInterface[]
     */
    private $formatters = array();

    /**
     * NEXT_MAJOR: Change function signature to __construct(Query $query, array $fields, array $formatters)
     *             Remove default set of formatters
     *
     * @param \Doctrine\ORM\Query      $query          The Doctrine Query
     * @param array                    $fields         Fields to export
     * @param string                   $dateTimeFormat
     * @param DataFormatterInterface[] $formatters     Array of data formatters
     */
    public function __construct(Query $query, array $fields, $dateTimeFormat = 'r')
    {
        $this->query = clone $query;
        $this->query->setParameters($query->getParameters());
        foreach ($query->getHints() as $name => $value) {
            $this->query->setHint($name, $value);
        }

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $this->propertyPaths = [];
        foreach ($fields as $name => $field) {
            if (is_string($name) && is_string($field)) {
                $this->propertyPaths[$name] = new PropertyPath($field);
            } else {
                $this->propertyPaths[$field] = new PropertyPath($field);
            }
        }

        if (func_num_args() == 3 && is_string($dateTimeFormat)) {
            // NEXT_MAJOR: When removed, the code below initializing a default set of formatters should be rewritten as well
            @trigger_error('Passing a dateTimeFormat string as the 4th parameter is deprecated since 1.7.2, to be removed in 2.0. '.
                'Pass an array of DataFormatterInterfaces instead',
                E_USER_DEPRECATED);
        }

        $formatters = array(new ArrayFormatter(), new DateTimeFormatter($dateTimeFormat), new ObjectToStringFormatter());
        if (func_num_args() == 4) {
            $formatters = array_merge($formatters, (array)func_get_arg(3));
        }

        foreach ($formatters as $formatter) {
            $this->addFormatter($formatter);
        }
    }

    /**
     * @param DataFormatterInterface $formatter
     */
    private function addFormatter(DataFormatterInterface $formatter)
    {
        $this->formatters[$formatter->getPriority()] = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $current = $this->iterator->current();

        $data = [];

        foreach ($this->propertyPaths as $name => $propertyPath) {
            try {
                $data[$name] = $this->getValue($current[0], $propertyPath);
            } catch (UnexpectedTypeException $e) {
                //non existent object in path will be ignored
                $data[$name] = null;
            }
        }

        $this->query->getEntityManager()->getUnitOfWork()->detach($current[0]);

        return $data;
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

        ksort($this->formatters);

        $this->iterator = $this->query->iterate();
        $this->iterator->rewind();
    }

    /**
     * @param string $dateTimeFormat
     */
    public function setDateTimeFormat($dateTimeFormat)
    {
        @trigger_error('Passing a dateTimeFormat string is deprecated since 1.7.2, to be removed in 2.0. '.
            'The source should be initialized in the constructor with an array of DataFormatterInterfaces instead',
            E_USER_DEPRECATED);

        $this->dateTimeFormat = $dateTimeFormat;
        $newFormatter = new DateTimeFormatter($dateTimeFormat);
        $this->formatters[$newFormatter->getPriority()] = $newFormatter;
    }

    /**
     * NEXT_MAJOR: Remove
     *
     * @return string
     * @deprecated Deprecated since 1.7.1, to be removed in 2.0
     */
    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }

    /**
     * NEXT_MAJOR: Change function signature to getValue($target, PropertyPath $propertyPath), keep code between if block,
     *             and drop the else code block
     *
     * @param mixed        $target
     * @param PropertyPath $propertyPath
     *
     * @return mixed|array|string|object
     */
    protected function getValue($target)
    {
        if (func_num_args() == 2 && func_get_arg(1) instanceof PropertyPath) {
            $propertyPath = func_get_arg(1);
            $value = $this->propertyAccessor->getValue($target, $propertyPath);
        } else {
            @trigger_error('Passing a single value is deprecated since 1.7.2, to be removed in 2.0.'.
                'Pass the target object and a PropertyPath object that will retrieve the value instead',
                E_USER_DEPRECATED);

            $value = $target;
            $propertyPath = new PropertyPath('');
        }

        foreach ($this->formatters as $formatter) {
            if ($formatter->supports($value)) {
                return $formatter->format($value, $propertyPath);
            }
        }

        return $value;
    }
}
