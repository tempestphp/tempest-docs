<?php

namespace Tests\Highlight\Patterns\Html;

use App\Highlight\Patterns\Html\TagAttributePattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class TagAttributePatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new TagAttributePattern(),
            content: htmlentities('<x-hello attr="">'),
            expected: 'attr',
        );

        $this->assertMatches(
            pattern: new TagAttributePattern(),
            content: htmlentities('<a href="">'),
            expected: 'href',
        );
    }
}
