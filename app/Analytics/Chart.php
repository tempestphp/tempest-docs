<?php

namespace App\Analytics;

use Tempest\Support\ArrayHelper;

final readonly class Chart
{
    public function __construct(
        public ArrayHelper $labels,
        public ArrayHelper $values,
    ) {}
}