<?php

namespace App\Web;

use Tempest\Router\Get;
use Tempest\Router\Responses\Redirect;

final readonly class RedirectsController
{
    #[Get('/discord')]
    public function discord(): Redirect
    {
        return new Redirect('https://discord.gg/pPhpTGUMPQ');
    }

    #[Get('/github')]
    public function github(): Redirect
    {
        return new Redirect('https://github.com/tempestphp/tempest-framework');
    }

    #[Get('/bluesky')]
    public function bluesky(): Redirect
    {
        return new Redirect('https://bsky.app/profile/brendt.bsky.social');
    }

    #[Get('/twitter')]
    public function twitter(): Redirect
    {
        return new Redirect('https://x.com/brendt_gd');
    }
}
