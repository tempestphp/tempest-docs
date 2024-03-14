<?php

namespace App\Highlight;

interface Language
{
    public function parse(string $content, Theme $theme): string;
}