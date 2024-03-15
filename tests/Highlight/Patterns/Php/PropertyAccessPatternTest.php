<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\PropertyAccessPattern;
use App\Highlight\Patterns\Php\PropertyTypesPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class PropertyAccessPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new PropertyAccessPattern(),
            content: htmlentities('$this->foo'),
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new PropertyAccessPattern(),
            content: htmlentities('$this->foo()'),
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new PropertyAccessPattern(),
            content: htmlentities('$obj->foo'),
            expected: 'foo',
        );
    }
}
