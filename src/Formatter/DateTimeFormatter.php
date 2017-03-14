<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Formatter;

use Symfony\Component\PropertyAccess\PropertyPath;

final class DateTimeFormatter implements DataFormatterInterface
{
    /** @var null|string */
    private $format = null;

    /**
     * DateTimeFormatter constructor.
     * @param null|string $dateTimeFormat
     */
    public function __construct($dateTimeFormat = 'r')
    {
        $this->format = $dateTimeFormat;
    }

    /**
     * @inheritDoc
     * NEXT_MAJOR: can drop instanceof \DateTime can be removed when dropping php < 5.5
     */
    public function supports($data)
    {
        return $data instanceof \DateTime || $data instanceof \DateTimeInterface;
    }

    /**
     * @inheritDoc
     */
    public function format($data, PropertyPath $propertyPath)
    {
        return $data->format($this->format);
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 100;
    }
}
