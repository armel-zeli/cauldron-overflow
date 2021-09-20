<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class MarkdownExtension extends AbstractExtension
{
    private MarkdownHelper $markdownHelper;

    /**
     * @param MarkdownHelper $markdownHelper Service to handle markdown
     */
    public function __construct(MarkdownHelper $markdownHelper)
    {

        $this->markdownHelper = $markdownHelper;
    }

    /**
     * @return TwigFilter[] Our new filter
     */
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('parse_markdown', [$this, 'parseMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $value The value that should be parsed
     *
     * @return string The value parsed in markdown format
     * @throws \Psr\Cache\InvalidArgumentException If the argument function are invalid
     */
    public function parseMarkdown($value)
    {
        return $this->markdownHelper->parse($value);
    }
}
