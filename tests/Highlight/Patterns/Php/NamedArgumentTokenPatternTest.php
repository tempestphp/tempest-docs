<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NamedArgumentTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NamedArgumentTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NamedArgumentTokenPattern(),
            content: '#[Foo(prop: hi)]',
            expected: 'prop',
        );

        $this->assertMatches(
            pattern: new NamedArgumentTokenPattern(),
            content: 'foo(bar: $a, baz: $b)',
            expected: ['bar', 'baz'],
        );

        $this->assertMatches(
            pattern: new NamedArgumentTokenPattern(),
            content: 'foo(
                bar: $a, 
                baz: $b
            )',
            expected: ['bar', 'baz'],
        );
    }
}
