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

/**
 * NEXT_MAJOR: Remove this interface.
 *
 * @deprecated since sonata-project/exporter 2.9 use \Iterator instead.
 *
 * @phpstan-extends \Iterator<array<mixed>>
 */
interface SourceIteratorInterface extends \Iterator
{
}
