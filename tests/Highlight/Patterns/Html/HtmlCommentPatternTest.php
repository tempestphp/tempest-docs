<?php

namespace Tests\Highlight\Patterns\Html;

use App\Highlight\Patterns\Html\HtmlCommentPattern;
use App\Highlight\Patterns\Html\OpenTagPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class HtmlCommentPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new HtmlCommentPattern(),
            content: htmlentities('
            test
            <!-- 
            foo
            -->
            test
            >'),
            expected: htmlentities('<!-- 
            foo
            -->'),
        );
    }
}
