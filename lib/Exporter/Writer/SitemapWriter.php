<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exporter\Writer;

/**
 * Generates a sitemap site from
 *
 */
class SitemapWriter implements WriterInterface
{
    const LIMIT_SIZE = 10485760;
    const LIMIT_URL  = 50000;

    protected $folder;

    protected $pattern;

    protected $buffer;

    protected $bufferSize = 0;

    protected $bufferUrlCount = 0;

    protected $bufferPart = 0;


    /**
     * @param string $folder
     */
    public function __construct($folder)
    {
        $this->folder  = $folder;
        $this->pattern = 'sitemap_%05d.xml';
    }

    /**
     * @return void
     */
    public function open()
    {
        $this->bufferPart = 0;
        $this->generateNewPart();
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function write(array $data)
    {
        $this->addSitemapLine(
            isset($data['url']) ? $data['url'] : null,
            isset($data['lastmod']) ? $data['lastmod'] : 'now',
            isset($data['changefreq']) ? $data['changefreq'] : 'weekly',
            isset($data['priority']) ? $data['priority'] : 0.5
        );
    }

    /**
     * @return void
     */
    public function close()
    {
        if ($this->buffer) {
            $this->closeSitemap();
        }

        $this->generateSitemapIndex();
    }

    /**
     * Generates the sitemap index from the sitemap part avaible in the folder
     */
    public function generateSitemapIndex()
    {
        $content = "<?xml version='1.0' encoding='UTF-8'?" . ">\n<sitemapindex xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/1.0 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd' xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
        foreach (glob($this->folder.'/sitemap*.xml') as $file) {
            $stat = stat($file);
            $content .= sprintf("\t" . '<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>' . "\n",
                basename($file),
                date('Y-m-d', $stat['mtime'])
            );
        }

        $content .= '</sitemapindex>';

        file_put_contents($this->folder.'/sitemap.xml', $content);
    }

    /**
     * Generate a new sitemap part
     *
     * @throws \RuntimeException
     */
    protected function generateNewPart()
    {
        if ($this->buffer) {
            $this->closeSitemap();
        }

        $this->bufferUrlCount = 0;
        $this->bufferSize     = 0;
        $this->bufferPart++;

        if (!is_writable($this->folder)) {
            throw new \RuntimeException(sprintf('Unable to write to folder: %s', $this->folder));
        }

        $filename = sprintf($this->pattern, $this->bufferPart);

        $this->buffer = fopen($this->folder . '/' . $filename, 'w');

        $this->bufferSize += fwrite($this->buffer, '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n");
    }

    /**
     * Add a new line into the sitemap part
     *
     * @param string $url
     * @param string $lastmod
     * @param string $changefreq
     * @param float  $priority
     */
    protected function addSitemapLine($url, $lastmod, $changefreq, $priority)
    {
        $line = sprintf("\t".'<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%s</priority></url>'."\n", $url, date('Y-m-d', strtotime($lastmod)), $changefreq, $priority);

        if ($this->bufferUrlCount >= self::LIMIT_URL) {
            $this->generateNewPart();
        }

        if (($this->bufferSize + strlen($line) + 9) > self::LIMIT_SIZE) {
            $this->generateNewPart();
        }

        $this->bufferUrlCount++;

        $this->bufferSize += fwrite($this->buffer, $line);
    }

    /**
     * Close the sitemap part
     */
    protected function closeSitemap()
    {
        fwrite($this->buffer, '</urlset>');
        fclose($this->buffer);
    }
}