<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\TokenPattern;
use App\Highlight\Patterns\IsTokenPattern;
use App\Highlight\TokenType;

final readonly class GenericTokenPattern implements TokenPattern
{
    use IsTokenPattern;

    public function __construct(
        private string $pattern,
        private TokenType $tokenType,
    ) {}

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getTokenType(): TokenType
    {
        return $this->tokenType;
    }
}