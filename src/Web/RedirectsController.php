<?php

declare(strict_types=1);

namespace App\Web;

use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;

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

    #[Get('/bluesky/brent')]
    public function blueskyBrent(): Redirect
    {
        return new Redirect('https://bsky.app/profile/stitcher.io');
    }

    #[Get('/twitter/brent')]
    public function twitterBrent(): Redirect
    {
        return new Redirect('https://x.com/brendt_gd');
    }

    #[Get('/bluesky/enzo')]
    public function blueskyEnzo(): Redirect
    {
        return new Redirect('https://bsky.app/profile/innocenzi.dev');
    }

    #[Get('/twitter/enzo')]
    public function twitterEnzo(): Redirect
    {
        return new Redirect('https://x.com/enzoinnocenzi');
    }

    #[Get('/twitter/aidan')]
    public function twitterAidan(): Redirect
    {
        return new Redirect('https://x.com/HelloAidanCasey');
    }
}
