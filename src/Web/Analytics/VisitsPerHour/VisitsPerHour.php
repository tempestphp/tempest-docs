<?php

declare(strict_types=1);

namespace App\Web\Analytics\VisitsPerHour;

use DateTimeImmutable;
use Tempest\Database\IsDatabaseModel;

final class VisitsPerHour
{
    use IsDatabaseModel;

    public function __construct(
        private(set) DateTimeImmutable $date,
        private(set) int $count,
    ) {}

    public function increment(): self
    {
        $this->count += 1;

        return $this;
    }
}
