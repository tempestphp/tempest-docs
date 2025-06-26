<?php

namespace App\Web\Analytics;

final class AnalyticsConfig
{
    public function __construct(
        private(set) string $accessLogPath,
    ) {}
}
