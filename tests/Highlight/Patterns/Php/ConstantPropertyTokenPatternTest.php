<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ConstantPropertyTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class ConstantPropertyTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ConstantPropertyTokenPattern(),
            content: 'Foo::BAR',
            expected: 'BAR',
        );

        $this->assertMatches(
            pattern: new ConstantPropertyTokenPattern(),
            content: 'Foo::BAR()',
            expected: 'BAR',
        );
    }
}
