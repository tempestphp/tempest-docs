<?php

namespace App\Web;

use App\GitHub\GetStargazersCount;
use Tempest\Router\Request;
use Tempest\View\View;
use Tempest\View\ViewProcessor;

use function Tempest\Support\Str\strip_end;

/**
 * This processor is used to add a `<base>` tag without the trailing slash,
 * allowing the documentation to use relative links to other chapters.
 */
final readonly class BaseUriViewProcessor implements ViewProcessor
{
    public function __construct(
        private Request $request,
    ) {
    }

    #[\Override]
    public function process(View $view): View
    {
        return $view->data(baseUri: strip_end($this->request->uri, suffix: '/'));
    }
}
