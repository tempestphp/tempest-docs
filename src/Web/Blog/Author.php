<?php

namespace Tempest\Web\Blog;

enum Author: string
{
    case BRENT = 'brent';

    public function getName(): string
    {
        return match ($this) {
            self::BRENT => 'Brent',
        };
    }

    public function getBluesky(): string
    {
        return match ($this) {
            self::BRENT => 'brendt.bsky.social',
        };
    }

    public function getX(): string
    {
        return match ($this) {
            self::BRENT => 'brendt_gd',
        };
    }
}
