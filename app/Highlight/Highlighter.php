<?php

namespace App\Highlight;

use App\Highlight\Languages\PhpLanguage;
use App\Highlight\Languages\ViewLanguage;
use App\Highlight\Patterns\Php\GenericTokenPattern;

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

                    if (! $content) {
                        return $matches[0];
                    }

                    return str_replace(
                        search: $content,
                        replace: $closure($content, $this),
                        subject: $matches[0],
                    );
                },
                $content,
            );
        }

        // Token patterns
        /** @var Token[] $tokens */
        $tokens = [];

        foreach ($language->getTokenPatterns() as $key => $pattern) {
            if ($pattern instanceof TokenType) {
                $pattern = new GenericTokenPattern(
                    $key,
                    $pattern,
                );
            }

            $matches = $pattern->match($content);

            $match = $matches['match'] ?? null;

            if (! $match) {
                continue;
            }

            foreach ($match as $item) {
                $offset = $item[1];
                $value = $item[0];

                $token = new Token(
                    offset: $offset,
                    value: $value,
                    type: $pattern->getTokenType(),
                    pattern: $pattern,
                );

                if (! $this->tokenAlreadyPresent($tokens, $token)) {
                    $tokens[] = $token;
                }
            }
        }

        return (new RenderTokens())($content, $tokens);
    }

    private function tokenAlreadyPresent(array $tokens, Token $token): bool
    {
        foreach ($tokens as $tokenToCompare) {
            if ($tokenToCompare->equals($token)) {
                return true;
            }
        }

        return false;
    }
}