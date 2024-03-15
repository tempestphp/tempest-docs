<?php

namespace App\Highlight\Injections;

use App\Highlight\Highlighter;
use App\Highlight\Injection;

final readonly class PhpShortEchoInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '/&lt;\?=\s(?<match>.*)\s\?&gt;/';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, 'php');
    }
}