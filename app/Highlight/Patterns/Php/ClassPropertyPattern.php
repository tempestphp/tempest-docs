<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class ClassPropertyPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(public|private|protected)\s([\(\)\|\&\?\w]+)\s(?<match>\$[\w]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::PROPERTY;
    }
}