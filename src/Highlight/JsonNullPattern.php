<?php

declare(strict_types=1);

namespace App\Highlight;

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

    #[\Override]
    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-null');
    }
}
