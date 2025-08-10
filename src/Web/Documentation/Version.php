<?php

namespace App\Web\Documentation;

use Tempest\Support\IsEnumHelper;

enum Version: string
{
    use IsEnumHelper;

    case VERSION_1 = '1.x';
    case VERSION_2 = '2.x';

    public static function default(): self
    {
        return self::VERSION_1;
    }

    public function isNext(): bool
    {
        return match ($this) {
            self::VERSION_2 => true,
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
        return $this->value;
    }

    public function isPrevious(): bool
    {
        return ! $this->isNext() && ! $this->isCurrent();
    }

    public function isCurrent(): bool
    {
        return $this === self::default();
    }

    public static function tryFromString(?string $case): ?static
    {
        return match ($case) {
            'default', 'current', 'main', null => self::default(),
            'next' => array_find(self::cases(), fn (self $version) => $version->isNext()),
            default => self::tryFrom($case),
        };
    }
}
