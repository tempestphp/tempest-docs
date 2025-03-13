<?php

namespace App\Analytics;

use Tempest\Support\Arr\ImmutableArray;

final readonly class Chart
{
    public function __construct(
        public ImmutableArray $labels,
        public ImmutableArray $values,
    ) {
    }
}
