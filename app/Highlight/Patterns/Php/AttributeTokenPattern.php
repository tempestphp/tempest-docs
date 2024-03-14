<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsTokenPattern;
use App\Highlight\TokenPattern;
use App\Highlight\TokenType;

final readonly class AttributeTokenPattern implements TokenPattern
{
    use IsTokenPattern;

    public function getPattern(): string
    {
        return '\#\[(?<match>[\w]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}