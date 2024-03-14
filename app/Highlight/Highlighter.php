<?php

namespace App\Highlight;

use App\Highlight\Languages\PhpLanguage;
use App\Highlight\Languages\ViewLanguage;

final class Highlighter
{
    public function __construct(
        /** @var \App\Highlight\Language[] */
        private array $languages = [],
    )
    {
        $this->languages['php'] = new PhpLanguage();
        $this->languages['view'] = new ViewLanguage();
    }

    public function parse(string $content, string $language): string
    {
        $language = $this->languages[$language] ?? null;

        if (! $language) {
            return $content;
        }

        $parsed = [];

        $lines = explode(PHP_EOL, $content);

        foreach ($lines as $line) {
            $parsed[] = $this->parseLine($line, $language);
        }

        return implode(PHP_EOL, $parsed);
    }

    private function parseLine(string $content, Language $language): string
    {
        $tokenRules = $language->getTokenRules();

        /** @var Token[] $tokens */
        $tokens = [];

        foreach ($tokenRules as $rule => $tokenType) {
            preg_match_all("/$rule/", $content, $matches, PREG_OFFSET_CAPTURE);

            $match = $matches['match'] ?? null;

            if (! $match) {
                continue;
            }

            foreach ($match as $item) {
                $offset = $item[1];
                $value = $item[0];

                $tokens[$offset] = new Token($offset, $value, $tokenType);
            }
        }

        ksort($tokens);

        $parsed = $content;
        $parsedOffset = 0;

        foreach ($tokens as $offset => $token) {
            $parsedToken = $token->type->parse($token->value);

            $parsed = substr_replace(
                $parsed,
                $parsedToken,
                $offset + $parsedOffset,
                $token->length,
            );

            $parsedOffset += strlen($parsedToken) - $token->length;
        }

        foreach ($language->getLineRules() as $rule => $tokenType) {
            preg_match("/$rule/", $parsed, $matches);

            $match = $matches[0] ?? null;

            if (! $match) {
                continue;
            }
            
            $parsed = $tokenType->parse($parsed);
        }

        return $parsed;
    }
}