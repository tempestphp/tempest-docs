<?php

namespace App\Web\Homepage;

use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\view;

final readonly class HomeController
{
    #[StaticPage]
    #[Get('/')]
    public function __invoke(): View
    {
        return view('./home.view.php');
    }
}
