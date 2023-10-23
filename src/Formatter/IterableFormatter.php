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

namespace Sonata\Exporter\Formatter;

final class IterableFormatter implements FormatterInterface
{
    public function format(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!is_iterable($value)) {
                continue;
            }

            if (!\is_array($value)) {
                $value = iterator_to_array($value);
            }

            $data[$key] = '['.array_reduce($value, static function (?string $carry, mixed $item): string {
                $item = (string) $item;

                if (null === $carry) {
                    return $item;
                }

                return $carry.', '.$item;
            }).']';
        }

        return $data;
    }
}
