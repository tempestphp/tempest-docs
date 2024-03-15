<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\KeywordPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class KeywordPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new KeywordPattern('match'),
            content: 'match ()',
            expected: 'match',
        );

        $this->assertMatches(
            pattern: new KeywordPattern('return'),
            content: 'return ()',
            expected: 'return',
        );

        $this->assertMatches(
            pattern: new KeywordPattern('class'),
            content: 'class ()',
            expected: 'class',
        );
    }
}
