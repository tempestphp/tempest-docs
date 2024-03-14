<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\StaticClassCallTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class StaticClassCallTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new StaticClassCallTokenPattern(),
            content: 'Foo::bar()',
            expected: 'Foo',
        );

        $this->assertMatches(
            pattern: new StaticClassCallTokenPattern(),
            content: 'Foo::BAR',
            expected: 'Foo',
        );
    }
}
