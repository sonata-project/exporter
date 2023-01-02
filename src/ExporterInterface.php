<?php

declare(strict_types=1);

namespace Sonata\Exporter;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface ExporterInterface
{
    /**
     * @return string[]
     */
    public function getAvailableFormats(): array;

    public function getResponse(string $format, string $filename, \Iterator $source): StreamedResponse;
}
