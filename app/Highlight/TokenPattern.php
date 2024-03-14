<?php

namespace App\Highlight;

interface TokenPattern
{
    public function match(string $content): array;

    public function getTokenType(): TokenType;
}