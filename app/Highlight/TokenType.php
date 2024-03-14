<?php

namespace App\Highlight;

enum TokenType: string
{
    case KEYWORD = 'keyword';
    case PROPERTY = 'property';
    case ATTRIBUTE = 'attribute';
    case TYPE = 'type';
    case GENERIC = 'generic';
    case VALUE = 'value';
    case COMMENT = 'comment';

    public function parse(string $value): string
    {
        $class = match ($this) {
            TokenType::KEYWORD => 'hl-keyword',
            TokenType::PROPERTY => 'hl-property',
            TokenType::TYPE => 'hl-type',
            TokenType::GENERIC => 'hl-generic',
            TokenType::VALUE => 'hl-value',
            TokenType::COMMENT => 'hl-comment',
            TokenType::ATTRIBUTE => 'hl-attribute',
        };

        return "<span class=\"{$class}\">{$value}</span>";
    }
}