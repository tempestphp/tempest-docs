<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\KeywordTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class KeywordTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new KeywordTokenPattern('match'),
            content: 'match ()',
            expected: 'match',
        );

        $this->assertMatches(
            pattern: new KeywordTokenPattern('return'),
            content: 'return ()',
            expected: 'return',
        );

        $this->assertMatches(
            pattern: new KeywordTokenPattern('class'),
            content: 'class ()',
            expected: 'class',
        );
    }
}
