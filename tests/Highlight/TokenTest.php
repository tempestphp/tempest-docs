<?php

namespace Tests\Highlight;

use App\Highlight\RenderTokens;
use App\Highlight\Token;
use App\Highlight\TokenType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    #[Test]
    public function test_nested_tokens(): void
    {
        $content = '/** @var \Tempest\View\GenericView $this */';

        $tokens = [
            new Token(
                offset: 0,
                value: '/** @var \Tempest\View\GenericView $this */',
                type: TokenType::COMMENT,
            ),
            new Token(
                offset: 23,
                value: 'GenericView',
                type: TokenType::TYPE,
            )
        ];

        $parsed = (new RenderTokens())($content, $tokens);
        
        $this->assertSame(
            '<span class="hl-comment">/** @var \Tempest\View\<span class="hl-type">GenericView</span> $this */</span>',
            $parsed,
        );
    }
}