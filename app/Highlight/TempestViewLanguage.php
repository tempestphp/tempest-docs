<?php

declare(strict_types=1);

namespace App\Highlight;

use App\Highlight\Injections\TempestViewEchoInjection;
use App\Highlight\Injections\TempestViewPhpInjection;
use App\Highlight\Patterns\TempestViewDynamicAttributePattern;
use Tempest\Highlight\Languages\Html\HtmlLanguage;

final class TempestViewLanguage extends HtmlLanguage
{
    public function getName(): string
    {
        return 'html';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            new TempestViewPhpInjection(),
            new TempestViewEchoInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new TempestViewDynamicAttributePattern(),
        ];
    }
}
