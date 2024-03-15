<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\NamespacePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class NamespacePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NamespacePattern(),
            content: 'namespace Foo\Bar',
            expected: 'Foo\Bar',
        );
    }
}
