<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class ClassNamePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'class (?<match>[\w]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}