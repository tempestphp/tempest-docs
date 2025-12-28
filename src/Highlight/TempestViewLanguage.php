<?php

declare(strict_types=1);

namespace App\Highlight;

use App\Highlight\Injections\TempestViewEchoInjection;
use App\Highlight\Injections\TempestViewPhpInjection;
use App\Highlight\Patterns\TempestViewCommentPattern;
use App\Highlight\Patterns\TempestViewDynamicAttributePattern;
use Override;
use Tempest\Highlight\Languages\Html\HtmlLanguage;

final class TempestViewLanguage extends HtmlLanguage
{
    #[Override]
    public function getName(): string
    {
        return 'html';
    }

    #[Override]
    public function getAliases(): array
    {
        return [];
    }

    #[Override]
    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            new TempestViewPhpInjection(),
            new TempestViewEchoInjection(),
        ];
    }

    #[Override]
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new TempestViewDynamicAttributePattern(),
            new TempestViewCommentPattern(),
        ];
    }
}
