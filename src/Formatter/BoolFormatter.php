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

final class BoolFormatter implements FormatterInterface
{
    private const LABEL_TRUE = 'yes';
    private const LABEL_FALSE = 'no';

    public function __construct(
        private string $trueLabel = self::LABEL_TRUE,
        private string $falseLabel = self::LABEL_FALSE
    ) {
    }

    public function format(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!\is_bool($value)) {
                continue;
            }

            $data[$key] = $value ? $this->trueLabel : $this->falseLabel;
        }

        return $data;
    }
}
