<?php

namespace App\Front\Home;

use League\CommonMark\MarkdownConverter;
use Tempest\Http\Get;
use Tempest\Http\StaticPage;
use Tempest\View\View;
use function Tempest\Support\arr;
use function Tempest\view;

final readonly class HomeController
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    #[StaticPage]
    #[Get('/')]
    public function __invoke(): View
    {
        $codeBlocks = arr(glob(__DIR__ . '/*.md'))
            ->mapWithKeys(function (string $path) {
                preg_match('/(\d+).md/', $path, $matches);

                $index = $matches[1];

                return yield "code{$index}" => $this->markdown->convert(file_get_contents($path));
            });

        return view(__DIR__ . '/home.view.php', ...$codeBlocks);
    }
}