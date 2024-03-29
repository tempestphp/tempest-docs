<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\StaticClassCallPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class StaticClassCallPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new StaticClassCallPattern(),
            content: 'Foo::bar()',
            expected: 'Foo',
        );

        $this->assertMatches(
            pattern: new StaticClassCallPattern(),
            content: 'Foo::BAR',
            expected: 'Foo',
        );
    }
}
