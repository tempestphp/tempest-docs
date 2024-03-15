<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class MultilineDoubleDocCommentPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '/(?<match>\/\*\*(.|\n)*\*\/)/m';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::COMMENT;
    }
}