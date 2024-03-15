<?php

namespace Tests\Highlight\Injections;

use App\Highlight\Highlighter;
use App\Highlight\Injections\PhpInjection;
use PHPUnit\Framework\TestCase;

class PhpInjectionTest extends TestCase
{
    /** @test */
    public function test_injection(): void
    {
        $content = htmlentities('
<?php 
    /** @var \Tempest\View\GenericView $this */
    $var = new Foo(); 
?>

Hello, <?= $this->name ?>
        ');
        
        $highlighter = new Highlighter();
        $injection = new PhpInjection();
        
        $output = $injection->parse($content, $highlighter);

        $this->assertStringContainsString(
            '<span class="hl-comment">/** @var',
            $output,
        );
    }
}
