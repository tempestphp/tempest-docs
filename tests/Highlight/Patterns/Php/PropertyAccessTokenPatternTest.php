<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\PropertyAccessTokenPattern;
use App\Highlight\Patterns\Php\PropertyTypesTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class PropertyAccessTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new PropertyAccessTokenPattern(),
            content: htmlentities('$this->foo'),
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new PropertyAccessTokenPattern(),
            content: htmlentities('$this->foo()'),
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new PropertyAccessTokenPattern(),
            content: htmlentities('$obj->foo'),
            expected: 'foo',
        );
    }
}
