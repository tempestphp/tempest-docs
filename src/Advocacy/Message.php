<?php

namespace App\Advocacy;

final class Message
{
    public function __construct(
        public string $message,
        public string $uri,
    ) {}
}