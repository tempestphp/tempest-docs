<?php

namespace App\Front\Home;

use Tempest\Http\Get;
use Tempest\Http\Responses\Redirect;

final readonly class DiscordController
{
    #[Get('/discord')]
    public function __invoke(): Redirect
    {
        return new Redirect('https://discord.gg/pPhpTGUMPQ');
    }
}