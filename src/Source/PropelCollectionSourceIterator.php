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

/**
 * Read data from a PropelCollection.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 *
 * NEXT_MAJOR: Remove this class.
 *
 * @deprecated since exporter 2.12 to be removed in 3.0.
 */
final class PropelCollectionSourceIterator extends AbstractPropertySourceIterator
{
    private \PropelCollection $collection;

    /**
     * @param array<string> $fields Fields to export
     */
    public function __construct(\PropelCollection $collection, array $fields, string $dateTimeFormat = 'r')
    {
        @trigger_error(sprintf(
            'The %s class is deprecated since sonata-project/exporter 2.12, to be removed in version 3.0.',
            self::class,
        ), \E_USER_DEPRECATED);

        $this->collection = clone $collection;

        parent::__construct($fields, $dateTimeFormat);
    }

    public function rewind(): void
    {
        if (null === $this->iterator) {
            $this->iterator = $this->collection->getIterator();
        }

        $this->iterator->rewind();
    }
}
