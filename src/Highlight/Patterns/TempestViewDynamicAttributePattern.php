<?php

declare(strict_types=1);

namespace App\Highlight\Patterns;

use Override;
use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\TokenTypeEnum;

final readonly class TempestViewDynamicAttributePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '<.*? :(?<match>\w+)';
    }

    #[Override]
    public function getTokenType(): TokenType
    {
        return TokenTypeEnum::PROPERTY;
    }
}
