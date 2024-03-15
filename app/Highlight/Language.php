<?php

namespace App\Highlight;

interface Language
{
    /**
     * @return array<string, \Closure>
     */
    public function getInjectionPatterns(): array;

    /**
     * @return array<string, \App\Highlight\TokenType>|\App\Highlight\TokenPattern[]
     */
    public function getTokenPatterns(): array;
}