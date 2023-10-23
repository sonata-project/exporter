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

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SymfonyTranslationFormatter implements FormatterInterface
{
    /**
     * @param array<string, string> $parameters
     */
    public function __construct(
        private TranslatorInterface $translator,
        private array $parameters = [],
        private ?string $domain = null,
        private ?string $locale = null
    ) {
    }

    public function format(array $data): array
    {
        foreach ($data as $key => $value) {
            if (\is_string($value)) {
                $data[$key] = $this->translator->trans($value, $this->parameters, $this->domain, $this->locale);

                continue;
            }

            if ($value instanceof TranslatableInterface) {
                $data[$key] = $value->trans($this->translator, $this->locale);
            }
        }

        return $data;
    }
}
