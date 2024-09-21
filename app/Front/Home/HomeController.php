<?php

namespace App\Front\Home;

use League\CommonMark\MarkdownConverter;
use Tempest\Http\Get;
use Tempest\View\View;
use function Tempest\Support\arr;
use function Tempest\view;

final readonly class HomeController
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    #[Get('/')]
    public function __invoke(): View
    {
        $codeBlocks = arr(['1', '2'])
            ->mapWithKeys(fn (string $index) => yield "code{$index}" => $this->markdown->convert(file_get_contents(__DIR__ . "/code-{$index}.md")));

        return view(__DIR__ . '/home.view.php', ...$codeBlocks);
    }
}