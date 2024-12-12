<?php

namespace App\Analytics\VisitsPerDay;

use DateTimeImmutable;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;

final class VisitsPerDay implements DatabaseModel
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