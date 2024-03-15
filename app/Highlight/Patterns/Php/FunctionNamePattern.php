<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class FunctionNamePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'function (?<match>[\w]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::PROPERTY;
    }
}