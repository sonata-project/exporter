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
use Sonata\Exporter\Formatter\SymfonyTranslationFormatter;
use Sonata\Exporter\Writer\InMemoryWriter;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @requires PHP >= 8.1
 */
final class SymfonyTranslationFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFormatterCases
     */
    public function testFormatter(string|TranslatableInterface $value, SymfonyTranslationFormatter $formatter, string $expected): void
    {
        $data = [
            'name' => 'john',
            'lastname' => 'doe',
            'translatable' => $value,
        ];
        $writer = new InMemoryWriter();
        $writer->addFormatter($formatter);
        $writer->open();
        $writer->write($data);

        $exportedItems = $writer->getElements();

        static::assertArrayHasKey(0, $exportedItems);

        $firstItem = $exportedItems[0];

        static::assertSame($expected, $firstItem['translatable']);

        $writer->close();
    }

    /**
     * @phpstan-return iterable<array{0: string|TranslatableInterface, 1: SymfonyTranslationFormatter, 2: string}>
     */
    public function provideFormatterCases(): iterable
    {
        $translator = new class() implements TranslatorInterface {
            /**
             * @phpstan-var array<string, array<string, array<string, string>>>
             */
            private array $catalog = [
                'messages' => [
                    'en' => [
                        'hello' => 'Hello',
                        'greeting' => 'Hello {name}!',
                    ],
                    'es' => [
                        'hello' => 'Hola',
                        'greeting' => '¡Hola {name}!',
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

        yield ['hello', new SymfonyTranslationFormatter($translator), 'Hello'];
        yield ['hello', new SymfonyTranslationFormatter($translator, locale: 'es'), 'Hola'];
        yield ['greeting', new SymfonyTranslationFormatter($translator, ['{name}' => 'Javier']), 'Hello Javier!'];
        yield ['greeting', new SymfonyTranslationFormatter($translator, ['{name}' => 'Javier'], 'messages', 'es'), '¡Hola Javier!'];

        $translatable = new class('greeting', ['{name}' => 'Javier']) implements TranslatableInterface {
            /**
             * @param array<mixed> $parameters
             */
            public function __construct(private string $message, private array $parameters = [], private ?string $domain = null)
            {
            }

            public function trans(TranslatorInterface $translator, ?string $locale = null): string
            {
                return $translator->trans($this->message, $this->parameters, $this->domain, $locale);
            }
        };

        yield [$translatable, new SymfonyTranslationFormatter($translator, locale: 'es'), '¡Hola Javier!'];
    }
}
