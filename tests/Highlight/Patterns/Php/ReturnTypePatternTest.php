<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ReturnTypePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class ReturnTypePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ReturnTypePattern(),
            content: 'function foo(): Bar',
            expected: 'Bar',
        );

        $this->assertMatches(
            pattern: new ReturnTypePattern(),
            content: 'function foo(): Foo|Bar',
            expected: 'Foo|Bar',
        );

        $this->assertMatches(
            pattern: new ReturnTypePattern(),
            content: 'function foo(): Foo&Bar',
            expected: 'Foo&Bar',
        );

        $this->assertMatches(
            pattern: new ReturnTypePattern(),
            content: 'function foo(): (Foo&Bar)|null',
            expected: '(Foo&Bar)|null',
        );
    }
}
