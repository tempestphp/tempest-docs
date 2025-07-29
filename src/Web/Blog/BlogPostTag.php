<?php

namespace App\Web\Blog;

enum BlogPostTag: string
{
    case RELEASE = 'release';
    case THOUGHTS = 'thoughts';
    case TUTORIAL = 'tutorial';

    public function getStyle(): string
    {
        return match($this) {
            self::THOUGHTS => 'ring-amber-200 text-amber-400',
            self::RELEASE => 'ring-blue-200 text-blue-400',
            self::TUTORIAL => 'ring-teal-200 text-teal-400',
        };
    }
}