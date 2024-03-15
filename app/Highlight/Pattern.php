<?php

namespace App\Highlight;

interface Pattern
{
    public function match(string $content): array;

    public function getTokenType(): TokenType;
}