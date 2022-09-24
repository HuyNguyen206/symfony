<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class MarkdownHelper {


    public function __construct(private MarkdownParserInterface $markdownParser, private bool $isDebug)
    {}

    public function parse(string $source): string
    {
        if (stripos($source, 'cat') !== false) {
            $this->logger->info('Meow!');
        }
        if ($this->isDebug) {
            return $this->markdownParser->transformMarkdown($source);
        }
        return $this->cache->get('markdown_'.md5($source), function() use ($source) {
            return $this->markdownParser->transformMarkdown($source);
        });
    }
}