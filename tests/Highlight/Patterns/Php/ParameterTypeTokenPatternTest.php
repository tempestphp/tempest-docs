<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ParameterTypePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class ParameterTypeTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ParameterTypePattern(),
            content: 'function foo(Bar $bar, Baz $baz)',
            expected: ['Bar', 'Baz'],
        );
    }
}
