<?php

declare(strict_types=1);

namespace App\Highlight\Injections;

use App\Highlight\IsTagInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Injection;

final readonly class StrongInjection implements Injection
{
    use IsTagInjection;

    public function getTag(): string
    {
        return 'strong';
    }

    public function style(string $content): string
    {
        return sprintf(
            '%s%s%s',
            Escape::tokens('<span class="hl-console-strong">'),
            $content,
            Escape::tokens('</span>'),
        );
    }
}
