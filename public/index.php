<?php

use Tempest\Framework\Tempest;

require_once __DIR__ . '/../vendor/autoload.php';

Tempest::boot(__DIR__ . '/../')->http()->run();

exit;