<?php

namespace App\Web\Documentation;

use Tempest\Support\IsEnumHelper;

enum Version: string
{
    use IsEnumHelper;

    case VERSION_1 = 'main';
    case VERSION_2 = '2.x';

    public function getBranch(): string
    {
        return match ($this) {
            self::VERSION_1 => 'main',
            self::VERSION_2 => '2.x',
        };
    }

    public function getUrlSegment(): string
    {
        return match ($this) {
            self::VERSION_1 => 'main',
            self::VERSION_2 => '2.x',
        };
    }

    public static function tryFromString(?string $case): ?static
    {
        return match ($case) {
            'default', 'current', null => self::default(),
            default => self::tryFrom($case),
        };
    }

    public static function default(): self
    {
        return self::VERSION_1;
    }
}
