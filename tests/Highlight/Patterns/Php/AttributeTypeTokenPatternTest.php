<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\AttributeTypeTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class AttributeTypeTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new AttributeTypeTokenPattern(),
            content: '#[Foo(prop: hi)]',
            expected: 'Foo',
        );

        $this->assertMatches(
            pattern: new AttributeTypeTokenPattern(),
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
