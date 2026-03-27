<?php

use App\Advocacy\Discord\DiscordConfig;

return new DiscordConfig(
    webhookUrl: env('DISCORD_WEBHOOK_URL'),
);