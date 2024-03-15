<?php

namespace App\Highlight\Languages;

use App\Highlight\Injections\PhpInjection;
use App\Highlight\Injections\PhpShortEchoInjection;
use App\Highlight\Language;
use App\Highlight\Patterns\Html\CloseTagPattern;
use App\Highlight\Patterns\Html\HtmlCommentPattern;
use App\Highlight\Patterns\Html\OpenTagPattern;
use App\Highlight\Patterns\Html\TagAttributePattern;
use App\Highlight\Tokens\TokenType;

final class HtmlLanguage implements Language
{
    public function getInjections(): array
    {
        return [
            new PhpInjection(),
            new PhpShortEchoInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            new OpenTagPattern(),
            new CloseTagPattern(),
            new TagAttributePattern(),
            new HtmlCommentPattern(),
        ];
    }
}