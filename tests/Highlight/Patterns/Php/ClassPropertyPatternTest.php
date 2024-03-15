<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ClassPropertyPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class ClassPropertyPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ClassPropertyPattern(),
            content: 'public Foo $foo',
            expected: '$foo',
        );

        $this->assertMatches(
            pattern: new ClassPropertyPattern(),
            content: 'public Foo|Bar $foo',
            expected: '$foo',
        );

        $this->assertMatches(
            pattern: new ClassPropertyPattern(),
            content: 'public Foo&Bar $foo',
            expected: '$foo',
        );

        $this->assertMatches(
            pattern: new ClassPropertyPattern(),
            content: 'public (Foo&Bar)|null $foo',
            expected: '$foo',
        );
    }
}
