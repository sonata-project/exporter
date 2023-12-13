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

use Sonata\Exporter\Formatter\FormatterInterface;

abstract class AbstractWriter implements FormatAwareInterface
{
    /**
     * @var array<int, FormatterInterface>
     */
    protected array $formatters = [];

    public function addFormatter(FormatterInterface $formatter): void
    {
        $this->formatters[] = $formatter;
    }

    /**
     * @param array<int|string, mixed> $data
     *
     * @return array<int|string, mixed>
     */
    protected function format(array $data): array
    {
        foreach ($this->formatters as $formatter) {
            $data = $formatter->format($data);
        }

        return $data;
    }
}
