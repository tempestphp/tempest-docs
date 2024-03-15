<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class FunctionCallPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '\b(?<match>[a-z][\w]+)\(';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::PROPERTY;
    }
}