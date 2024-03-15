<?php

namespace Tests\Highlight\Injections;

use App\Highlight\Highlighter;
use App\Highlight\Injections\CssInjection;
use PHPUnit\Framework\TestCase;

class CssInjectionTest extends TestCase
{
    /** @test */
    public function test_injection(): void
    {
        $content = htmlentities('
<x-slot name="styles">
    <style>
        body {
            background-color: red;
        }
    </style>
</x-slot>
        ');

        $highlighter = new Highlighter();
        $injection = new CssInjection();

        $output = $injection->parse($content, $highlighter);

        $this->assertStringContainsString(
            '<span class="hl-property">background-color</span>',
            $output,
        );
    }
}
