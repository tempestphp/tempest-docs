<?php

namespace Tests\Highlight;

use App\Highlight\Token;
use App\Highlight\TokenType;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    /** @test */
    public function test_contains(): void
    {
        $a = new Token(
            offset: 10,
            value: 'abc',
            type: TokenType::VALUE,
        );

        $b = new Token(
            offset: 11,
            value: 'b',
            type: TokenType::VALUE,
        );

        $this->assertTrue($a->contains($b));
        $this->assertFalse($b->contains($a));
    }
}