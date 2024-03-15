<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NestedFunctionCallPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NestedFunctionCallTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NestedFunctionCallPattern(),
            content: ' foo()',
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new NestedFunctionCallPattern(),
            content: '(foo()',
            expected: 'foo',
        );
    }
}
