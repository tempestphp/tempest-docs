<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsTokenPattern;
use App\Highlight\TokenPattern;
use App\Highlight\TokenType;

final readonly class AttributeTypeTokenPattern implements TokenPattern
{
    use IsTokenPattern;

    public function getPattern(): string
    {
        return '/(^[\s]*|\#\[)(?<match>[A-Z][\w]+)/m';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}