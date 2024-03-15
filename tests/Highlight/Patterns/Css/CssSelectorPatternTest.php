<?php

namespace Tests\Highlight\Patterns\Css;

use App\Highlight\Patterns\Css\CssSelectorPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class CssSelectorPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new CssSelectorPattern(),
            content: 'code, .asd, #id,
.hl-blur, @font-face,
kbd, samp,
pre {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}
',
            expected: 'code, .asd, #id,
.hl-blur, @font-face,
kbd, samp,
pre ',
        );
    }
}
