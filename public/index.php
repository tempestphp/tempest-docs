<?php

use Tempest\Router\HttpApplication;

require_once __DIR__ . '/../vendor/autoload.php';

HttpApplication::boot(__DIR__ . '/../')->run();

exit();
