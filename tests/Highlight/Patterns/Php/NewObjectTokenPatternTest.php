<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NewObjectPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NewObjectTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NewObjectPattern(),
            content: 'new Foo()',
            expected: 'Foo',
        );

        $this->assertMatches(
            pattern: new NewObjectPattern(),
            content: '(new Foo)',
            expected: 'Foo',
        );
    }
}
