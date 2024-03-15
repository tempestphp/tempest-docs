<?php

namespace App\Highlight;

interface Injection
{
    public function parse(string $content, Highlighter $highlighter): string;
}