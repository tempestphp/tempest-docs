<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\AttributePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class AttributePatternTest extends TestCase
{
    use TestsPatterns;

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
