<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NamedArgumentPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class NamedArgumentPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NamedArgumentPattern(),
            content: '#[Foo(prop: hi)]',
            expected: 'prop',
        );

        $this->assertMatches(
            pattern: new NamedArgumentPattern(),
            content: 'foo(bar: $a, baz: $b)',
            expected: ['bar', 'baz'],
        );

        $this->assertMatches(
            pattern: new NamedArgumentPattern(),
            content: 'foo(
                bar: $a, 
                baz: $b
            )',
            expected: ['bar', 'baz'],
        );
    }
}
