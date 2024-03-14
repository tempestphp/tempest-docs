<?php

namespace App\Highlight\Languages;

use App\Highlight\Highlighter;
use App\Highlight\Language;
use App\Highlight\TokenType;

final class ViewLanguage implements Language
{
    public function getInjectionPatterns(): array
    {
        return [
            '&lt;\?=\s(?<match>.*)\s\?&gt;' => fn (string $match, Highlighter $highlighter) => $highlighter->parse($match, 'php'),
            '\&lt;\?php(?<match>(.|\n)*?)\?&gt;' => fn (string $match, Highlighter $highlighter) => $highlighter->parse($match, 'php'),
        ];
    }

    public function getTokenPatterns(): array
    {
        return [
            '&lt;(?<match>[\w\-]+)' => TokenType::KEYWORD,
            '&lt;\/(?<match>[\w\-]+)' => TokenType::KEYWORD,
            '(?<match>[\w]+)=&quot;' => TokenType::PROPERTY,
            '(?<match>\&lt;!--(.|\n)*--&gt;)' => TokenType::COMMENT,
        ];
    }

    public function getLinePatterns(): array
    {
        return [];
    }
}