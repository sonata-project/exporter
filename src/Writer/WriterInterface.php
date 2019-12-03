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

namespace Sonata\Exporter\Writer;

interface WriterInterface
{
    /**
     * @throws \RuntimeException
     */
    public function open();

    /**
     * @throws \RuntimeException
     */
    public function write(array $data);

    public function close();
}
