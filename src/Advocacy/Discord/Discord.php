<?php

namespace App\Advocacy\Discord;

use App\Advocacy\Message;

final readonly class Discord
{
    public function __construct(
        private DiscordConfig $config,
    ) {}

    public function notify(Message $message): void
    {
        // TODO: format message and send to Discord webhook
    }
}