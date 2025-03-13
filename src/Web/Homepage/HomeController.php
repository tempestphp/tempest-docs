<?php

namespace Tempest\Web\Homepage;

use League\CommonMark\MarkdownConverter;
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
