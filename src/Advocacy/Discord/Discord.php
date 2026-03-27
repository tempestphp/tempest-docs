<?php

declare(strict_types=1);

namespace App\Advocacy\Discord;

use App\Advocacy\Message;
use Tempest\HttpClient\HttpClient;

final readonly class Discord
{
    public function __construct(
        private DiscordConfig $config,
        private HttpClient $http,
    ) {}

    public function notify(Message $message): void
    {
        $this->http->post(
            uri: $this->config->webhookUrl,
            headers: ['Content-Type' => 'application/json'],
            body: json_encode([
                'content' => $message->uri,
            ]),
        );
    }
}
