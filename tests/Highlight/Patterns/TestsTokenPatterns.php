<?php

namespace Tests\Highlight\Patterns;

use App\Highlight\TokenPattern;

trait TestsTokenPatterns
{
    public function assertMatches(
        TokenPattern $pattern,
        string $content,
        string|array $expected,
    ): void {
        $matches = $pattern->match($content);

        if (is_string($expected)) {
            $expected = [$expected];
        }

        foreach ($expected as $key => $expectedValue) {
            $this->assertSame($expectedValue, $matches['match'][$key][0]);
        }
    }
}