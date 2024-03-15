<?php

namespace App\Highlight\Patterns\Html;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class CloseTagPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '&lt;\/(?<match>[\w\-]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::KEYWORD;
    }
}