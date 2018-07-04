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

/**
 * Interface to implement for data formatters that will handle some
 * input and format the output as desired for exporting. For example
 * this can be used to format DateTime instances or any other objects
 * that require custom or additional logic when exported as a string.
 */
interface DataFormatterInterface
{
    /**
     * Determine if we know how to format this particular data type
     *
     * @param $data
     * @return bool
     */
    public function supports($data);

    /**
     * Formats data
     *
     * @param $data
     * @param PropertyPath $propertyPath
     * @return string
     */
    public function format($data, PropertyPath $propertyPath);

    /**
     * Returns where in the formatting stack this should land must be greater than 0
     * Lower priority is processed first. The first formatter found that supports a
     * particular data type is called and no further processing occurs.
     *
     * @return int
     */
    public function getPriority();
}
