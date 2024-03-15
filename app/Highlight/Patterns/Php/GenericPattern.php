<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Pattern;
use App\Highlight\Patterns\IsPattern;
use App\Highlight\TokenType;

final readonly class GenericPattern implements Pattern
{
    use IsPattern;

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