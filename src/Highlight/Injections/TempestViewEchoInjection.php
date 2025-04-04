<?php

namespace App\Highlight\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Languages\Php\PhpLanguage;

final readonly class TempestViewEchoInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '({{|{!!)(?<match>.*)(!!}|}})';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, new PhpLanguage());
    }
}
