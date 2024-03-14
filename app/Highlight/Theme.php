<?php

namespace App\Highlight;

interface Theme
{
    public function parse(string $content, Token $token): string;
}