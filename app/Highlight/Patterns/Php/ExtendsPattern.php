<?php

namespace App\Highlight\Patterns\Php;

use App\Highlight\Patterns\IsPattern;
use App\Highlight\Pattern;
use App\Highlight\TokenType;

final readonly class ExtendsPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'extends\s(?<match>.*)$';
    }

    public function getTokenType(): TokenType
    {
        return TokenType::TYPE;
    }
}