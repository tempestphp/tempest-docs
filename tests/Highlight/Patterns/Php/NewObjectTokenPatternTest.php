<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NewObjectTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NewObjectTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NewObjectTokenPattern(),
            content: 'new Foo()',
            expected: 'Foo',
        );

        $this->assertMatches(
            pattern: new NewObjectTokenPattern(),
            content: '(new Foo)',
            expected: 'Foo',
        );
    }
}
