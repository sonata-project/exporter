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

namespace Sonata\Exporter\Tests\Source;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Source\ChainSourceIterator;

final class ChainSourceIteratorTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testIterator(): void
    {
        $source = $this->createMock(\Iterator::class);

        $iterator = new ChainSourceIterator([$source]);

        foreach ($iterator as $data) {
        }
    }

    #[DoesNotPerformAssertions]
    public function testEmptyIterator(): void
    {
        $iterator = new ChainSourceIterator([]);

        foreach ($iterator as $data) {
        }
    }
}
