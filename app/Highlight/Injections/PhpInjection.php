<?php

namespace App\Highlight\Injections;

use App\Highlight\Highlighter;
use App\Highlight\Injection;

final readonly class PhpInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '\&lt;\?php(?<match>(.|\n)*?)\?&gt;';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, 'php');
    }
}