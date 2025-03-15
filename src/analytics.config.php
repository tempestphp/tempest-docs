<?php

use App\Web\Analytics\AnalyticsConfig;

use function Tempest\env;

return new AnalyticsConfig(
    accessLogPath: env('ACCESS_LOG_PATH', 'logs/access.log'),
);
