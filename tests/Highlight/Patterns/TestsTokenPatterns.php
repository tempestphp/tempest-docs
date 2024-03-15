<?php

namespace Tests\Highlight\Patterns;

use App\Highlight\Pattern;

trait TestsTokenPatterns
{
    public function assertMatches(
        Pattern $pattern,
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