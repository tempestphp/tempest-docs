<?php

namespace App\Analytics;

final class AnalyticsConfig
{
    public function __construct(
        private(set) string $accessLogPath,
    ) {}
}