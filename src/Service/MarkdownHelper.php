<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    private $cache;
    private $parser;
    private $isDebug;
    private $logger;

    /**
     * Constructor class
     *
     * @param CacheInterface $cache Service to handle cache
     * @param MarkdownParserInterface $parser Service to handle markdown notation
     * @param bool $isDebug Is mode debug enabled or not ?
     * @param LoggerInterface $mdLogger Service to log something
     */
    public function __construct(
        CacheInterface $cache,
        MarkdownParserInterface $parser,
        bool $isDebug,
        LoggerInterface $mdLogger
    ) {
        $this->cache = $cache;
        $this->parser = $parser;
        $this->isDebug = $isDebug;
        $this->logger = $mdLogger;
    }

    /**
     * Parse a $source to return that $source in a markdown format
     *
     * @param string $source
     *
     * @return string Return the source parsed
     * @throws \Psr\Cache\InvalidArgumentException If arguments of are invalid
     */
    public function parse(string $source): string
    {
        if (strpos($source, 'cat') !== false) {
            $this->logger->info('Meaow !');
        }
        if ($this->isDebug) {
            return $this->parser->transformMarkdown($source);
        }

        return $this->cache->get('markdown_'.md5($source), function () use ($source) {
            return $this->parser->transformMarkdown($source);
        });
    }
}