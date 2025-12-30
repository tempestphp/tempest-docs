<?php

declare(strict_types=1);

namespace App\Web\Blog;

enum BlogPostTag: string
{
    case RELEASE = 'release';
    case THOUGHTS = 'thoughts';
    case TUTORIAL = 'tutorial';

    public function getStyle(): string
    {
        return match ($this) {
            self::THOUGHTS => 'bg-yellow-400/20 dark:bg-yellow-400/10 text-yellow-700 dark:text-yellow-400',
            self::RELEASE => 'bg-blue-400/20 dark:bg-blue-400/10 text-blue-700 dark:text-blue-400',
            self::TUTORIAL => 'bg-teal-400/20 dark:bg-teal-400/10 text-teal-700 dark:text-teal-400',
        };
    }
}
