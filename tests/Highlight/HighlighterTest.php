<?php

namespace Tests\Highlight;

use App\Highlight\Highlighter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HighlighterTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function test_highlight(string $slug, string $language): void
    {
        $highlight = new Highlighter();

        $content = file_get_contents(__DIR__ . "/stubs/{$slug}.txt");
        [$input, $output] = explode('===', $content);

        $this->assertSame(
            trim($output),
            trim($highlight->parse($input, $language)),
        );
    }

    public static function data(): array
    {
        return [
            ['01', 'php'],
//            ['02', 'php'],
//            ['03', 'php'],
        ];
    }
}
