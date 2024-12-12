<?php

declare(strict_types=1);

namespace App\Support\Highlight;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\DynamicTokenType;
use Tempest\Highlight\Tokens\TokenType;

final readonly class JsonNullPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '\: (?<match>null)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-null');
    }
}
