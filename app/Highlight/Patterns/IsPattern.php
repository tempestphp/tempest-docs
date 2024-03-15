<?php

namespace App\Highlight\Patterns;

trait IsPattern
{
    public abstract function getPattern(): string;

    public function match(string $content): array
    {
        $pattern = $this->getPattern();

        if (! str_starts_with($pattern, '/')) {
            $pattern = "/$pattern/";
        }

        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }
}