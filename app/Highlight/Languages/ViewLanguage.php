<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\TokenType;

final class ViewLanguage implements Language
{
    public function getLineRules(): array
    {
        return [];
    }

    public function getTokenRules(): array
    {
        return [
            '&lt;(?<match>[\w]+)' => TokenType::KEYWORD,
            '&lt;\/(?<match>[\w]+)' => TokenType::KEYWORD,
            '(?<match>[\w]+)=&quot;' => TokenType::PROPERTY,
        ];
    }
}