<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    private $cache;
    private $parser;

    /**
     * Constructor class
     *
     * @param CacheInterface $cache Service to handle cache
     * @param MarkdownParserInterface $parser Service to handle markdown notation
     */
    public function __construct(CacheInterface $cache, MarkdownParserInterface $parser)
    {
        $this->cache = $cache;
        $this->parser = $parser;
    }

    /**
     * Parse a $source to return that $source in a markdown format
     *
     * @param string $source
     *
     * @return string Return the source parsed
     * @throws \Psr\Cache\InvalidArgumentException If arguments of are invalid
     */
    public function parse(string $source) : string
    {
        return $this->cache->get('markdown_'.md5($source), function () use($source){
            return $this->parser->transformMarkdown($source);
        });
    }
}