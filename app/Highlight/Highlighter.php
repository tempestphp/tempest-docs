<?php

namespace App\Highlight;

use App\Highlight\Languages\PhpLanguage;
use App\Highlight\Themes\Tempest;

final class Highlighter
{
    public function __construct(
        /** @var \App\Highlight\Language[] */
        private array $languages = []
    )
    {
        $this->languages['php'] = new PhpLanguage();
    }

    public function parse(string $content, string $language): string
    {
        $language = $this->languages[$language] ?? null;
        
        if ($language) {
            $content = $language->parse($content, new Tempest());
        }

        return $content;
    }
}