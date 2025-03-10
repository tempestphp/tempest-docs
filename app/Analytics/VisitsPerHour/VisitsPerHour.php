<?php

namespace App\Analytics\VisitsPerHour;

use DateTimeImmutable;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;

final class VisitsPerHour implements DatabaseModel
{
    use IsDatabaseModel;

    public function __construct(
        private(set) DateTimeImmutable $date,
        private(set) int $count,
    ) {
    }

    public function increment(): self
    {
        $this->count += 1;

        return $this;
    }
}
