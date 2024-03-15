<?php

namespace App\Highlight\Patterns\Html;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class HtmlCommentPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>\&lt;!--(.|\n)*--&gt;)';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::COMMENT;
    }
}