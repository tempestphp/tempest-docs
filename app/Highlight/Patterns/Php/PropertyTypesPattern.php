<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class PropertyTypesPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(public|private|protected)\s(?<match>[\(\)\|\&\?\w]+)\s\$';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}