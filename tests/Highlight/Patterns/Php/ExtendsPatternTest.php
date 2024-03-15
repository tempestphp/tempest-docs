<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ExtendsPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class ExtendsPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ExtendsPattern(),
            content: 'class Foo extends Bar',
            expected: 'Bar',
        );
    }
}
