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

namespace Sonata\Exporter\Source;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @phpstan-implements \Iterator<array<mixed>>
 */
final class SymfonySitemapSourceIterator implements \Iterator
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private \Iterator $source,
        private RouterInterface $router,
        private string $routeName,
        private array $parameters = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function current(): array
    {
        $data = $this->source->current();

        $parameters = array_merge($this->parameters, array_intersect_key($data, $this->parameters));

        if (!isset($data['url'])) {
            $data['url'] = $this->router->generate($this->routeName, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $data;
    }

    public function next(): void
    {
        $this->source->next();
    }

    public function key(): mixed
    {
        return $this->source->key();
    }

    public function valid(): bool
    {
        return $this->source->valid();
    }

    public function rewind(): void
    {
        $this->source->rewind();
    }
}
