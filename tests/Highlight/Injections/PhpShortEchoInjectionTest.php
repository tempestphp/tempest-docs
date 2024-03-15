<?php

namespace Tests\Highlight\Injections;

use App\Highlight\Highlighter;
use App\Highlight\Injections\PhpInjection;
use App\Highlight\Injections\PhpShortEchoInjection;
use PHPUnit\Framework\TestCase;

class PhpShortEchoInjectionTest extends TestCase
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
Hello, <?= $this->other ?>
        ');
        
        $highlighter = new Highlighter();
        $injection = new PhpShortEchoInjection();
        
        $output = $injection->parse($content, $highlighter);

        $this->assertStringContainsString(
            '<span class="hl-property">name</span>',
            $output,
        );

        $this->assertStringContainsString(
            '<span class="hl-property">other</span>',
            $output,
        );
    }
}
