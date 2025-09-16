<?php

namespace App\Highlight;

use Override;
use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;

final readonly class HighlighterInitializer implements Initializer
{
    #[Override]
    #[Singleton(tag: 'project')]
    public function initialize(Container $container): Highlighter
    {
        $highlighter = new Highlighter(new CssTheme());

        $highlighter
            ->addLanguage(new TempestViewLanguage())
            ->addLanguage(new TempestConsoleWebLanguage())
            ->addLanguage(new ExtendedJsonLanguage());

        return $highlighter;
    }
}
