<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\AttributeTypePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class AttributeTypePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new AttributeTypePattern(),
            content: '#[Foo(prop: hi)]',
            expected: 'Foo',
        );

        $this->assertMatches(
            pattern: new AttributeTypePattern(),
            content: '
#[Foo(
        uri: "/books/create",
        "/books/create",
    ),
    Bar(uri: "/books/create"),
    Baz,
]',
            expected: ['Foo', 'Bar', 'Baz'],
        );
    }
}
