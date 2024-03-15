<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\FunctionNamePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class FunctionNamePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new FunctionNamePattern(),
            content: 'function foo()',
            expected: 'foo',
        );
    }
}
