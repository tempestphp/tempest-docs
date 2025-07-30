<?php

namespace App\Web\Community;

enum CommunityPostType: string
{
    case BLOG = 'Blog';
    case VIDEO = 'Video';
    case PACKAGE = 'Package';

    public function getStyle(): string
    {
        return match ($this) {
            self::BLOG => 'ring-amber-200 text-amber-400',
            self::VIDEO => 'ring-blue-200 text-blue-400',
            self::PACKAGE => 'ring-teal-200 text-teal-400',
        };
    }
}
