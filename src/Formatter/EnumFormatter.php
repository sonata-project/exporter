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

final class EnumFormatter implements FormatterInterface
{
    public function __construct(
        private bool $useBackedEnumValue = true
    ) {
    }

    public function format(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!$value instanceof \UnitEnum) {
                continue;
            }

            if ($this->useBackedEnumValue && $value instanceof \BackedEnum) {
                $data[$key] = $value->value;

                continue;
            }

            $data[$key] = $value->name;
        }

        return $data;
    }
}
