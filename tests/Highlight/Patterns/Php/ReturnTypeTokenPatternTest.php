<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\ReturnTypeTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class ReturnTypeTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new ReturnTypeTokenPattern(),
            content: 'function foo(): Bar',
            expected: 'Bar',
        );

        $this->assertMatches(
            pattern: new ReturnTypeTokenPattern(),
            content: 'function foo(): Foo|Bar',
            expected: 'Foo|Bar',
        );

        $this->assertMatches(
            pattern: new ReturnTypeTokenPattern(),
            content: 'function foo(): Foo&Bar',
            expected: 'Foo&Bar',
        );

        $this->assertMatches(
            pattern: new ReturnTypeTokenPattern(),
            content: 'function foo(): (Foo&Bar)|null',
            expected: '(Foo&Bar)|null',
        );
    }
}
