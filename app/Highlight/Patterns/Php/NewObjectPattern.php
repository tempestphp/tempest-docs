<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class NewObjectPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'new (?<match>[\w]+)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}