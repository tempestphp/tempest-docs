<?php

namespace Tests\Highlight\Patterns\Html;

use App\Highlight\Patterns\Html\CloseTagPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class CloseTagPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new CloseTagPattern(),
            content: htmlentities('</x-hello>'),
            expected: 'x-hello',
        );

        $this->assertMatches(
            pattern: new CloseTagPattern(),
            content: htmlentities('</a>'),
            expected: 'a',
        );
    }
}
