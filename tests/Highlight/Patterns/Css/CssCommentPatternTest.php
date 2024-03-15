<?php

namespace Tests\Highlight\Patterns\Css;

use App\Highlight\Patterns\Css\CssCommentPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class CssCommentPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new CssCommentPattern(),
            content: '
    /* 1 */
    font-feature-settings: normal;
    /* 2 */
    font-variation-settings: normal;
',
            expected: ['/* 1 */', '/* 2 */'],
        );

        $this->assertMatches(
            pattern: new CssCommentPattern(),
            content: 'abc /* 1 */;',
            expected: '/* 1 */',
        );
    }
}
