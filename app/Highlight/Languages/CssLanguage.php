<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\Patterns\Css\CssAttributePattern;
use App\Highlight\Patterns\Css\CssCommentPattern;
use App\Highlight\Patterns\Css\CssFunctionPattern;
use App\Highlight\Patterns\Css\CssSelectorPattern;

class CssLanguage implements Language
{
    public function getInjections(): array
    {
        return [];
    }

    public function getPatterns(): array
    {
        return [
            new CssCommentPattern(),
            new CssSelectorPattern(),
            new CssAttributePattern(),
            new CssFunctionPattern(),
        ];
    }
}