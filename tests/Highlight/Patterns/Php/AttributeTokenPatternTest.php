<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\AttributePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class AttributeTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new AttributePattern(),
            content: '#[Foo(prop: hi)]',
            expected: '#[Foo(prop: hi)]',
        );

        $this->assertMatches(
            pattern: new AttributePattern(),
            content: '#[Foo(
                prop: hi,
            )]',
            expected: '#[Foo(
                prop: hi,
            )]',
        );
    }
}
