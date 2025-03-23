<?php

namespace App\Web\Homepage;

use League\CommonMark\MarkdownConverter;
use Tempest\Router\Get;
use Tempest\Router\Responses\Redirect;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\Support\Arr\map_with_keys;
use function Tempest\Support\Str\strip_end;
use function Tempest\view;

final readonly class HomeController
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    #[StaticPage]
    #[Get('/')]
    public function __invoke(): View
    {
        $codeBlocks = map_with_keys(glob(__DIR__ . '/codeblocks/*.md'), function (string $path) {
            return yield strip_end(basename($path), '.md') => $this->markdown->convert(file_get_contents($path));
        });

        return view('./home.view.php', codeBlocks: $codeBlocks);
    }

    #[Get('/view')]
    public function viewRedirect(): Redirect
    {
        return new Redirect('/main/framework/views');
    }

    #[Get('/console')]
    public function consoleRedirect(): Redirect
    {
        return new Redirect('/main/console/getting-started');
    }
}
