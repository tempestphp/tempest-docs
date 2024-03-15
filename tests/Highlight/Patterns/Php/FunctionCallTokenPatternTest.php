<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\FunctionCallPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class FunctionCallTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new FunctionCallPattern(),
            content: 'foo()',
            expected: 'foo',
        );
    }
}
