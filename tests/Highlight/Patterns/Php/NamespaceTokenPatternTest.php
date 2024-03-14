<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NamespaceTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NamespaceTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NamespaceTokenPattern(),
            content: 'namespace Foo\Bar',
            expected: 'Foo\Bar',
        );
    }
}
