<?php

namespace App\Highlight;

use App\Highlight\Languages\CssLanguage;
use App\Highlight\Languages\PhpLanguage;
use App\Highlight\Languages\HtmlLanguage;
use App\Highlight\Tokens\ParseTokens;
use App\Highlight\Tokens\RenderTokens;

final class Highlighter
{
    private array $languages = [];

    public function __construct()
    {
        $this->languages['php'] = new PhpLanguage();
        $this->languages['html'] = new HtmlLanguage();
        $this->languages['css'] = new CssLanguage();
    }

    public function parse(string $content, string $language): string
    {
        $language = $this->languages[$language] ?? null;

        if (! $language) {
            return $content;
        }

        return $this->parseContent($content, $language);
    }

    private function parseContent(string $content, Language $language): string
    {
        // Injections
        foreach ($language->getInjections() as $injection) {
            $content = $injection->parse($content, $this);
        }

        // Patterns
        $tokens = (new ParseTokens())($content, $language);

        return (new RenderTokens())($content, $tokens);
    }
}