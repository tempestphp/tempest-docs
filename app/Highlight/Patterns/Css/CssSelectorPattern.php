<?php

namespace App\Highlight\Patterns\Css;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class CssSelectorPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>[\@\-\#\.\w\s,\n]+)\{';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::KEYWORD;
    }
}