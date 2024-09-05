<?php

namespace App\Front\Home;

use App\Front\Docs\DocsController;
use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\Http\Responses\Redirect;
use function Tempest\uri;

final readonly class HomeController
{
    #[Get('/')]
    public function __invoke(): Response
    {
        return new Redirect(uri(DocsController::class, category: 'framework', slug: '01-getting-started'));
    }
}