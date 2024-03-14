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

        return $this->parseContent($content, $language);
    }

    private function parseContent(string $content, Language $language): string
    {
        // Language injection patterns
        foreach ($language->getInjectionPatterns() as $pattern => $closure) {
            $content = preg_replace_callback(
                "/$pattern/",
                function ($matches) use ($closure) {
                    $content = $matches['match'] ?? null;

                    if (!$content) {
                        return $matches[0];
                    }

                    return str_replace(
                        search: $content,
                        replace: $closure($content, $this),
                        subject: $matches[0]
                    );
                },
                $content,
            );
        }

        // Individual token patterns
        /** @var Token[] $tokens */
        $tokens = [];

        foreach ($language->getTokenPatterns() as $pattern => $tokenType) {
            preg_match_all("/$pattern/", $content, $matches, PREG_OFFSET_CAPTURE);

            $match = $matches['match'] ?? null;

            if (! $match) {
                continue;
            }

            foreach ($match as $item) {
                $offset = $item[1];
                $value = $item[0];

                $tokens[] = new Token(
                    offset: $offset,
                    value: $value,
                    type: $tokenType,
                    pattern: $pattern,
                    language: $language
                );
            }
        }

        $parsed = (new RenderTokens())($content, $tokens);

        // Full line patterns
        foreach ($language->getLinePatterns() as $pattern => $tokenType) {
            preg_match_all("/$pattern/", $parsed, $matches);

            $matches = $matches[0];

            foreach ($matches as $match) {
                $parsed = str_replace(
                    search: $match,
                    replace: $tokenType->parse($match),
                    subject: $parsed,
                );
            }
        }

        return $parsed;
    }
}