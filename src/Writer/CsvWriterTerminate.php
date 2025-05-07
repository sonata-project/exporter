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

namespace Sonata\Exporter\Writer;

/**
 * Filter CSV output to replace the default terminator while supporting active streams.
 */
final class CsvWriterTerminate extends \php_user_filter
{
    /**
     * @param resource $in
     * @param resource $out
     * @param int      $consumed
     * @param bool     $closing
     */
    public function filter($in, $out, &$consumed, $closing): int
    {
        $bucket = stream_bucket_make_writeable($in);
        while (null !== $bucket) {
            if (isset($this->params['terminate'])) {
                $newData = preg_replace('/([^\r])\n/', '$1'.$this->params['terminate'], $bucket->data);
                if (null !== $newData) {
                    $bucket->data = $newData;
                }
            }
            $consumed += (int) $bucket->datalen;
            stream_bucket_append($out, $bucket);
            $bucket = stream_bucket_make_writeable($in);
        }

        return \PSFS_PASS_ON;
    }
}
