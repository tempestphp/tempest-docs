<?php

declare(strict_types=1);

namespace App\Support\Highlight\Injections;

use App\Support\Highlight\IsTagInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Injection;

final readonly class DimInjection implements Injection
{
    use IsTagInjection;

    public function getTag(): string
    {
        return 'dim';
    }

    public function style(string $content): string
    {
        return sprintf(
            '%s%s%s',
            Escape::tokens('<span class="hl-console-dim">'),
            $content,
            Escape::tokens('</span>'),
        );
    }
}
