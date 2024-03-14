<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsTokenPattern;
use App\Highlight\TokenPattern;
use App\Highlight\TokenType;

final readonly class PropertyTypesTokenPattern implements TokenPattern
{
    use IsTokenPattern;

    public function getPattern(): string
    {
        return '(public|private|protected)\s(?<match>[\(\)\|\&\?\w]+)\s\$';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}