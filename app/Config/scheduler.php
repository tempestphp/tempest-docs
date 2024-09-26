<?php

use Tempest\Console\Scheduler\SchedulerConfig;
use function Tempest\env;

return new SchedulerConfig(
    path: env('SCHEDULER_PATH', 'php tempest'),
);