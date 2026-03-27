<?php

namespace App\Advocacy\Discord;

final class DiscordConfig
{
    public function __construct(
        public string $webhookUrl,
    ) {}
}