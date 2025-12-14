<?php

namespace App\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;

trait ResolvesInfoWords
{
    private function getInfoWords(FencedCode $code): array
    {
        if ($code->getInfo() === null || $code->getInfo() === '') {
            return [];
        }

        $words = [];
        $pattern = '/"([^"]*)"|(\S+)/';

        \preg_match_all($pattern, $code->getInfo(), $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $words[] = $match[1] !== '' ? $match[1] : $match[2];
        }

        return $words;
    }
}
