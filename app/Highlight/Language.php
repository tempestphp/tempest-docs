<?php

namespace App\Highlight;

interface Language
{
    /**
     * @return array<string, \App\Highlight\TokenType>
     */
    public function getLineRules(): array;

    /**
     * @return array<string, \App\Highlight\TokenType>
     */
    public function getTokenRules(): array;
}