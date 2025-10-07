<?php

namespace App\Web\Blog;

use App\Web\Meta\MetaType;
use DateTimeImmutable;
use Tempest\Cache\Cache;
use Tempest\DateTime\DateTime;
use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Ok;
use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\View\View;

use Tempest\View\ViewRenderer;
use function Tempest\view;

final readonly class BlogController
{
    #[Get('/blog')]
    #[StaticPage]
    public function index(BlogRepository $repository): View
    {
        $posts = $repository->all();

        return view('./index.view.php', posts: $posts, metaType: MetaType::BLOG);
    }

    #[Get('/blog/{slug}')]
    #[StaticPage(BlogDataProvider::class)]
    public function show(string $slug, BlogRepository $repository): Response|View
    {
        $post = $repository->find($slug);

        if (! $post || ! $post->published) {
            return new NotFound();
        }

        return view('./show.view.php', post: $post);
    }

    #[Get('/rss')]
    public function rss(
        Cache $cache,
        ViewRenderer $viewRenderer,
        BlogRepository $repository,
    ): Response
    {
        $xml = $cache->resolve(
            key: 'rss',
            callback: fn () => $viewRenderer->render(view('rss.view.php', posts: $repository->all(loadContent: true))),
            expiration: DateTime::now()->plusHours(1),
        );

        return new Ok($xml)
            ->addHeader('Content-Type', 'application/xml;charset=UTF-8');
    }
}
