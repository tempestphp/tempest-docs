<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ExtendsTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class ExtendsTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ExtendsTokenPattern(),
            content: 'class Foo extends Bar',
            expected: 'Bar',
        );
    }
}
