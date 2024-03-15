<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class ParameterTypePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>[\|\&\?\w]+)\s\$';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}