<?php

declare(strict_types=1);

namespace App\Web\Community;

enum CommunityPostType: string
{
    case BLOG = 'Blog';
    case VIDEO = 'Video';
    case PACKAGE = 'Package';

    public function getStyle(): string
    {
        return match ($this) {
            self::BLOG => 'bg-yellow-400/20 dark:bg-yellow-400/10 text-yellow-700 dark:text-yellow-400',
            self::VIDEO => 'bg-blue-400/20 dark:bg-blue-400/10 text-blue-700 dark:text-blue-400',
            self::PACKAGE => 'bg-teal-400/20 dark:bg-teal-400/10 text-teal-700 dark:text-teal-400',
        };
    }
}
