<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsTokenPattern;
use App\Highlight\TokenPattern;
use App\Highlight\TokenType;

final readonly class KeywordTokenPattern implements TokenPattern
{
    use IsTokenPattern;

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