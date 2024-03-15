<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\MultilineDoubleDocCommentPattern;
use App\Highlight\Patterns\Php\MultilineSingleDocCommentPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsPatterns;

class MultilineSingleDocCommentPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new MultilineSingleDocCommentPattern(),
            content: '
use App\Highlight\Token;

/* world
 * hello
 */
final class PhpLanguage implements Language
            ',
            expected: '/* world
 * hello
 */',
        );
    }
}
