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

namespace Sonata\Exporter\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Formatter\BoolFormatter;
use Sonata\Exporter\Formatter\DateTimeFormatter;
use Sonata\Exporter\Formatter\EnumFormatter;
use Sonata\Exporter\Formatter\StringableFormatter;
use Sonata\Exporter\Formatter\SymfonyTranslationFormatter;
use Sonata\Exporter\Tests\Source\Fixtures\ObjectWithToString;
use Sonata\Exporter\Tests\Source\Fixtures\Suit;
use Sonata\Exporter\Writer\InMemoryWriter;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FormatterTest extends TestCase
{
    /**
     * @requires PHP >= 8.1
     */
    public function testMultipleFormatters(): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'date' => new \DateTimeImmutable('1986-03-22 21:45:00'),
            'blocked' => true,
            'enabled' => false,
            'suitChoice' => Suit::Hearts,
            'stringable' => new ObjectWithToString('hello'),
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter(new DateTimeFormatter('Y-m-d'));
        $writer->addFormatter(new BoolFormatter('yes', 'no'));
        $writer->addFormatter(new EnumFormatter());
        $writer->addFormatter(new StringableFormatter());
        $writer->addFormatter(new SymfonyTranslationFormatter($this->getTranslator(), locale: 'es'));
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertIsArray($firstItem);
        static::assertSame('1986-03-22', $firstItem['date']);
        static::assertSame('Sí', $firstItem['blocked']);
        static::assertSame('no', $firstItem['enabled']);
        static::assertSame('H', $firstItem['suitChoice']);
        static::assertSame('hello', $firstItem['stringable']);

        $writer->close();
    }

    private function getTranslator(): TranslatorInterface
    {
        return new class() implements TranslatorInterface {
            /**
             * @phpstan-var array<string, array<string, array<string, string>>>
             */
            private array $catalog = [
                'messages' => [
                    'en' => [
                        'yes' => 'Yes',
                    ],
                    'es' => [
                        'yes' => 'Sí',
                    ],
                ],
            ];

            /**
             * @param array<mixed> $parameters
             */
            public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
            {
                $domain ??= 'messages';
                $locale ??= $this->getLocale();
                $message = $this->catalog[$domain][$locale][$id] ?? $id;

                return strtr($message, $parameters);
            }

            public function getLocale(): string
            {
                return 'en';
            }
        };
    }
}
