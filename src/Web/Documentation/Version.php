<?php

namespace App\Web\Documentation;

use Tempest\Support\IsEnumHelper;

enum Version: string
{
    use IsEnumHelper;

    case VERSION_1 = '1.x';

    public static function default(): self
    {
        return self::VERSION_1;
    }

    public static function tryFromString(?string $case): ?static
    {
        return match ($case) {
            'default', 'current', null => self::default(),
            default => self::tryFrom($case),
        };
    }
}
