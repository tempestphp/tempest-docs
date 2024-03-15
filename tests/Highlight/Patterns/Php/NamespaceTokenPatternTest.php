<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NamespacePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class NamespaceTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NamespacePattern(),
            content: 'namespace Foo\Bar',
            expected: 'Foo\Bar',
        );
    }
}
