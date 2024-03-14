<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NestedFunctionCallTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NestedFunctionCallTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NestedFunctionCallTokenPattern(),
            content: ' foo()',
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new NestedFunctionCallTokenPattern(),
            content: '(foo()',
            expected: 'foo',
        );
    }
}
