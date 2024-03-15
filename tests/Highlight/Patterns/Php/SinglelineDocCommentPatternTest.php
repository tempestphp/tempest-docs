<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\SinglelineDocCommentPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class SinglelineDocCommentPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new SinglelineDocCommentPattern(),
            content: '$bar // foo',
            expected: '// foo',
        );
    }
}
