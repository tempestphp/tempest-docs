<?php

namespace Tests\Highlight\Patterns\Css;

use App\Highlight\Patterns\Css\CssFunctionPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class CssFunctionPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new CssFunctionPattern(),
            content: '
src: url("fonts/MonaspaceArgon-Bold.woff") format("woff");
',
            expected: ['url', 'format'],
        );
    }
}
