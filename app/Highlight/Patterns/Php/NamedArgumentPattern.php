<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class NamedArgumentPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>[\w]+):\s';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::PROPERTY;
    }
}