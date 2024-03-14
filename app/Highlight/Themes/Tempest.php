<?php

namespace App\Highlight\Themes;

use App\Highlight\Style;
use App\Highlight\Theme;
use App\Highlight\Token;

final readonly class Tempest implements Theme
{
    public function parse(string $content, Token $token): string
    {
        $style = match($token) {
            Token::BACKGROUND => new Style(background: '#F3F3F3'),
            Token::KEYWORD => new Style(color: '#4F95D1'),
            Token::PROPERTY => new Style(color: '#46B960'),
            Token::TYPE => new Style(color: '#D14F57'),
            Token::GENERIC => new Style(color: '#9D3AF6'),
            Token::VALUE => new Style(color: '#515248'),
            Token::COMMENT => new Style(color: '#888888', italic: true),
            Token::ATTRIBUTE => new Style(italic: true),
        };

        return $style->apply($content);
    }
}