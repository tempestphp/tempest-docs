<?php

declare(strict_types=1);

use Tempest\DateTime\Duration;
use Tempest\Http\Session\Config\DatabaseSessionConfig;

return new DatabaseSessionConfig(
    expiration: Duration::days(30),
);
