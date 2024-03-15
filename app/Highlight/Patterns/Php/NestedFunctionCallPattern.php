<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class NestedFunctionCallPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(\s|\()(?<match>[\w]+)\(';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::PROPERTY;
    }
}