<?php

declare(strict_types=1);

namespace App\Advocacy\Discord;

final readonly class DiscordConfig
{
    public function __construct(
        public string $webhookUrl,
    ) {}
}
