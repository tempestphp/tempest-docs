<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class AttributeTypePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '/(^[\s]*|\#\[)(?<match>[A-Z][\w]+)/m';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}