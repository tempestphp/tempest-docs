<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\MultilineDoubleDocCommentPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class MultilineDoubleDocCommentPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new MultilineDoubleDocCommentPattern(),
            content: '
use App\Highlight\Token;

/**
 * @return a 
 */
final class PhpLanguage implements Language

/**
 * @return b 
 */
            ',
            expected: [
                '/**
 * @return a 
 */',
                '/**
 * @return b 
 */',

            ],
        );
    }
}
