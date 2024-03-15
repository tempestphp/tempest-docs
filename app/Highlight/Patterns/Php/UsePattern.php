<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class UsePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'use (?<match>[\w\\\\]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}