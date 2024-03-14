<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\AttributeTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class AttributeTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new AttributeTokenPattern(),
            content: '#[Foo(prop: hi)]',
            expected: 'Foo',
        );
    }
}
