<?php

namespace App\Highlight\Injections;

use App\Highlight\Highlighter;
use App\Highlight\Injection;

final readonly class CssInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '&lt;style&gt;(?<match>(.|\n)*)&lt;\/style&gt;';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, 'css');
    }
}