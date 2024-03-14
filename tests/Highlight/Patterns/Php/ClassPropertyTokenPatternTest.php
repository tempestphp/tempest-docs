<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ClassPropertyTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class ClassPropertyTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ClassPropertyTokenPattern(),
            content: 'public Foo $foo',
            expected: '$foo',
        );

        $this->assertMatches(
            pattern: new ClassPropertyTokenPattern(),
            content: 'public Foo|Bar $foo',
            expected: '$foo',
        );

        $this->assertMatches(
            pattern: new ClassPropertyTokenPattern(),
            content: 'public Foo&Bar $foo',
            expected: '$foo',
        );

        $this->assertMatches(
            pattern: new ClassPropertyTokenPattern(),
            content: 'public (Foo&Bar)|null $foo',
            expected: '$foo',
        );
    }
}
