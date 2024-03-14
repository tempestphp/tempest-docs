<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsTokenPattern;
use App\Highlight\TokenPattern;
use App\Highlight\TokenType;

final readonly class MultilineDoubleDocCommentTokenPattern implements TokenPattern
{
    use IsTokenPattern;

    public function getPattern(): string
    {
        return '/(?<match>\/\*\*(.|\n)*\*\/)/m';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::COMMENT;
    }
}