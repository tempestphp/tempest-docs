<?php

namespace App\Front\Home;

use Tempest\Router\Get;
use Tempest\Router\Responses\Redirect;

final readonly class DiscordController
{
    #[Get('/discord')]
    public function __invoke(): Redirect
    {
        return new Redirect('https://discord.gg/pPhpTGUMPQ');
    }
}
