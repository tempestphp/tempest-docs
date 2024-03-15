<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\Tokens\TokenType;

final readonly class KeywordPattern implements Pattern
{
    use IsPattern;

    public function __construct(private string $keyword) {}

    public function getPattern(): string
    {
        return "\b(?<match>{$this->keyword})\s";
    }

    public function getTokenType(): TokenType
    {
        return TokenType::KEYWORD;
    }
}