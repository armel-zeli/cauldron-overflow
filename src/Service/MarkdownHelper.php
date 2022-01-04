<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    private CacheInterface $cache;
    private MarkdownParserInterface $parser;
    private bool $isDebug;
    private LoggerInterface $logger;
    private Security $security;

    /**
     * Constructor class
     *
     * @param CacheInterface $cache Service to handle cache
     * @param MarkdownParserInterface $parser Service to handle markdown notation
     * @param bool $isDebug Is mode debug enabled or not ?
     * @param LoggerInterface $mdLogger Service to log something
     * @param Security $security
     */
    public function __construct(
        CacheInterface $cache,
        MarkdownParserInterface $parser,
        bool $isDebug,
        LoggerInterface $mdLogger,
        Security $security
    ) {
        $this->cache = $cache;
        $this->parser = $parser;
        $this->isDebug = $isDebug;
        $this->logger = $mdLogger;
        $this->security = $security;
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
        if (str_contains($source, 'cat') !== false) {
            $this->logger->info('Meaow !');
        }

        if($this->security->getUser()){
            $this->logger->info('Rendering markdow for {user}',[
                'user'=>$this->security->getUser()->getUserIdentifier()
            ]);
        }

        if ($this->isDebug) {
            return $this->parser->transformMarkdown($source);
        }

        return $this->cache->get('markdown_'.md5($source), function () use ($source) {
            return $this->parser->transformMarkdown($source);
        });
    }
}