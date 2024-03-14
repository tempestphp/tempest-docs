<?php

namespace App\Highlight;

final class RenderTokens
{
    /**
     * @param string $content
     * @param \App\Highlight\Token[] $tokens
     * @return string
     */
    public function __invoke(string $content, array $tokens): string
    {
        usort($tokens, fn (Token $a, Token $b) => $a->offset <=> $b->offset);

        $parsed = $content;
        $parsedOffset = 0;
        $bufferedOffset = 0;
//dump($tokens);
        foreach ($tokens as $key => $currentToken) {
            $tokenType = $currentToken->type;

            $before = $tokenType->before();
            $after = $tokenType->after();

            $parsedToken = $before . $currentToken->value . $after;

            $parsed = substr_replace(
                $parsed,
                $parsedToken,
                $currentToken->offset + $parsedOffset,
                $currentToken->length,
            );

            $parsedOffset += strlen($before);
            
            $nextToken = $tokens[$key + 1] ?? null;

            if ($nextToken && $nextToken->offset < $currentToken->length) {
                // If the next token is nested within the current token's scope,
                // we won't count the afterLength as an offset right now,
                // but postpone it util it actually should be counted.
                // This allows for nested tokens
                $bufferedOffset += strlen($after);
            } else {
                $parsedOffset += strlen($after) + $bufferedOffset;
                $bufferedOffset = 0;
            }
        }

        return $parsed;
    }
}