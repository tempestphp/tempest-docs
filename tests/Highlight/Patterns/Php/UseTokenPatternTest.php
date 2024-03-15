<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\UsePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class UseTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new UsePattern(),
            content: 'use Foo\Bar\Baz;',
            expected: 'Foo\Bar\Baz',
        );
    }
}
