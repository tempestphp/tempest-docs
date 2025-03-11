<?php

namespace App\Front\Home;

use League\CommonMark\MarkdownConverter;
use Tempest\HttpClient\HttpClient;
use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\View\View;
use Throwable;

use function Tempest\Support\arr;
use function Tempest\view;

final readonly class HomeController
{
    public function __construct(
        private MarkdownConverter $markdown,
        private HttpClient $httpClient,
    ) {
    }

    #[StaticPage]
    #[Get('/')]
    public function __invoke(): View
    {
        try {
            $commit = json_decode($this->httpClient->get('https://api.github.com/repos/tempestphp/tempest-framework/commits')->getBody())[0] ?? null;
        } catch (Throwable) {
            $commit = null;
        }

        $codeBlocks = arr(glob(__DIR__ . '/*.md'))
            ->mapWithKeys(function (string $path) {
                preg_match('/(\d+).md/', $path, $matches);

                $index = $matches[1];

                return yield "code{$index}" => $this->markdown->convert(file_get_contents($path));
            });

        return view(__DIR__ . '/home.view.php', ...$codeBlocks, commit: $commit);
    }
}
