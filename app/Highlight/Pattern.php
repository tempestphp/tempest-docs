<?php

namespace App\Highlight;

use App\Highlight\Tokens\TokenType;

interface Pattern
{
    public function match(string $content): array;

    public function getTokenType(): TokenType;
}