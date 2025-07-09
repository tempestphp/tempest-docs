<?php

namespace App\Web\Blog;

enum BlogPostTag: string
{
    case RELEASE = 'Release';
    case THOUGHTS = 'Thoughts';
    case TUTORIAL = 'Tutorial';

    public function getStyle(): string
    {
        return match($this) {
            self::THOUGHTS => 'ring-amber-200 text-amber-400',
            self::RELEASE => 'ring-blue-200 text-blue-400',
            self::TUTORIAL => 'ring-teal-200 text-teal-400',
        };
    }
}