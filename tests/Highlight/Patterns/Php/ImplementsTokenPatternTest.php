<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ImplementsPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class ImplementsTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ImplementsPattern(),
            content: 'class Foo implements Bar',
            expected: 'Bar',
        );
    }
}
