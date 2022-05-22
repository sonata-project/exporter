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

namespace Sonata\Exporter;

use Sonata\Exporter\Writer\WriterInterface;

final class Handler
{
    private \Iterator $source;

    private WriterInterface $writer;

    public function __construct(\Iterator $source, WriterInterface $writer)
    {
        $this->source = $source;
        $this->writer = $writer;
    }

    public function export(): void
    {
        $this->writer->open();

        foreach ($this->source as $data) {
            $this->writer->write($data);
        }

        $this->writer->close();
    }

    public static function create(\Iterator $source, WriterInterface $writer): self
    {
        return new self($source, $writer);
    }
}
