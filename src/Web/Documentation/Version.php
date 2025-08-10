<?php

namespace App\Web\Documentation;

use Tempest\Support\IsEnumHelper;

enum Version: string
{
    use IsEnumHelper;

    case VERSION_1 = '1.x';
    case VERSION_2 = '2.x';

    public function isNext(): bool
    {
        return match ($this) {
            self::VERSION_2 => true,
            default => false,
        };
    }

    public function isCurrent(): bool
    {
        return match ($this) {
            self::VERSION_1 => true,
            default => false,
        };
    }

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
            self::VERSION_1 => '1.x',
            self::VERSION_2 => '2.x',
        };
    }

    public function isPrevious(): bool
    {
        return ! $this->isNext() && ! $this->isCurrent();
    }

    public static function tryFromString(?string $case): ?static
    {
        return match ($case) {
            'default', 'current', 'main', null => self::default(),
            'next' => self::VERSION_2,
            default => self::tryFrom($case),
        };
    }

    public static function default(): self
    {
        return self::VERSION_1;
    }
}
