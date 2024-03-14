<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\PropertyTypesTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class PropertyTypesTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new PropertyTypesTokenPattern(),
            content: 'public Bar $bar',
            expected: 'Bar',
        );

        $this->assertMatches(
            pattern: new PropertyTypesTokenPattern(),
            content: 'protected Bar $bar',
            expected: 'Bar',
        );

        $this->assertMatches(
            pattern: new PropertyTypesTokenPattern(),
            content: 'private Bar $bar',
            expected: 'Bar',
        );

        $this->assertMatches(
            pattern: new PropertyTypesTokenPattern(),
            content: 'public Foo|Bar $bar',
            expected: 'Foo|Bar',
        );

        $this->assertMatches(
            pattern: new PropertyTypesTokenPattern(),
            content: 'public Foo|Bar&Baz $bar',
            expected: 'Foo|Bar&Baz',
        );

        $this->assertMatches(
            pattern: new PropertyTypesTokenPattern(),
            content: 'public (Bar&Baz)|null $bar',
            expected: '(Bar&Baz)|null',
        );
    }
}
