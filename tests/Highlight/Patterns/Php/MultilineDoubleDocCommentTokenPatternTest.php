<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\MultilineDoubleDocCommentTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class MultilineDoubleDocCommentTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new MultilineDoubleDocCommentTokenPattern(),
            content: '
use App\Highlight\Token;

/**
 * @return hello 
 */
final class PhpLanguage implements Language
            ',
            expected: '/**
 * @return hello 
 */',
        );
    }
}
