<?php

namespace App\Web\Documentation;

use Override;
use Tempest\Support\IsEnumHelper;

enum Version: string
{
    use IsEnumHelper;

    case MAIN = 'main';
    // case VERSION_1 = '1.x';

    public static function default(): self
    {
        return self::MAIN;
    }

    public static function tryFromString(?string $case): ?static
    {
        return match ($case) {
            'default', 'current', null => self::default(),
            default => self::tryFrom($case),
        };
    }
}
