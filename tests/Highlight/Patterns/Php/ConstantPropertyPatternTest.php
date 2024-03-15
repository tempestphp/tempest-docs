<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ConstantPropertyPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class ConstantPropertyPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ConstantPropertyPattern(),
            content: 'Foo::BAR',
            expected: 'BAR',
        );

        $this->assertMatches(
            pattern: new ConstantPropertyPattern(),
            content: 'Foo::BAR()',
            expected: 'BAR',
        );
    }
}
