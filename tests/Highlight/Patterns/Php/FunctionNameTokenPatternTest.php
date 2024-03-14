<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\FunctionNameTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class FunctionNameTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new FunctionNameTokenPattern(),
            content: 'function foo()',
            expected: 'foo',
        );
    }
}
