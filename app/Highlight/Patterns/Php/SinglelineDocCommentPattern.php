<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class SinglelineDocCommentPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>\/\/(.)*)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::COMMENT;
    }
}