<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ClassNamePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class ClassNamePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ClassNamePattern(),
            content: 'class Foo implements Bar',
            expected: 'Foo',
        );
    }
}
