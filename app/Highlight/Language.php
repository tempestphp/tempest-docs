<?php

namespace App\Highlight;

interface Language
{
    /**
     * @return \App\Highlight\Injection[]
     */
    public function getInjections(): array;

    /**
     * @return \App\Highlight\Pattern[]
     */
    public function getPatterns(): array;
}