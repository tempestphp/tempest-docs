<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\SinglelineDocCommentTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class SinglelineDocCommentTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new SinglelineDocCommentTokenPattern(),
            content: '$bar // foo',
            expected: '// foo',
        );
    }
}
