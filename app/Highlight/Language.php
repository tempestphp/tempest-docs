<?php

namespace App\Highlight;

interface Language
{
    /**
     * @return array<string, \Closure>
     */
    public function getInjectionPatterns(): array;

    /**
     * @return array<string, \App\Highlight\TokenType>
     */
    public function getLinePatterns(): array;

    /**
     * @return array<string, \App\Highlight\TokenType>
     */
    public function getTokenPatterns(): array;
}