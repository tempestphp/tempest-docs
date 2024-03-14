<?php

namespace Tests\Highlight\Patterns\Php;

use App\Highlight\Patterns\Php\MultilineDoubleDocCommentTokenPattern;
use App\Highlight\Patterns\Php\MultilineSingleDocCommentTokenPattern;
use PHPUnit\Framework\TestCase;
use Tests\Highlight\Patterns\TestsTokenPatterns;

class MultilineSingleDocCommentTokenPatternTest extends TestCase
{
    use TestsTokenPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new MultilineSingleDocCommentTokenPattern(),
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
