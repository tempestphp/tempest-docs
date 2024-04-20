<?php

declare(strict_types=1);

namespace App\Highlight\Injections;

use App\Highlight\IsTagInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Injection;

final readonly class CommentInjection implements Injection
{
    use IsTagInjection;

    public function getTag(): string
    {
        return 'comment';
    }

    public function style(string $content): string
    {
        $comment = implode(
            PHP_EOL,
            [
                '/*',
                ...array_map(
                    fn (string $line) => "* {$line}",
                    explode(PHP_EOL, $content),
                ),
                '*/',
            ],
        );

        return sprintf(
            '%s%s%s',
            Escape::tokens('<span class="hl-console-comment">'),
            $comment,
            Escape::tokens('</span>'),
        );
    }
}
