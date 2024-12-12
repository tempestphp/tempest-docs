<?php

use Tempest\Database\Connections\SQLiteConnection;
use Tempest\Database\DatabaseConfig;

return new DatabaseConfig(
    connection: new SQLiteConnection(
        path: __DIR__ . '/../database.sqlite',
    )
);