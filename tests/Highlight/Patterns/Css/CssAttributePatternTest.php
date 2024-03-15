<?php

namespace Tests\Highlight\Patterns\Css;

use App\Highlight\Patterns\Css\CssAttributePattern;
use App\Highlight\Patterns\Css\CssSelectorPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class CssAttributePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new CssAttributePattern(),
            content: '
.hl-comment {
    color: #888888;
    font-style: italic;
    font-family: "Radon", serif;
}
',
            expected: ['color', 'font-style', 'font-family'],
        );
    }
}
