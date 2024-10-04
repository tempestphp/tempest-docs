<?php

namespace App\Front\Blog;

use DateTimeImmutable;
use Tempest\Cache\Cache;
use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\Http\Responses\Ok;
use Tempest\View\View;
use Tempest\View\ViewRenderer;
use function Tempest\view;

final readonly class BlogController
{
    #[Get('/blog')]
    public function index(BlogRepository $repository): View
    {
        $posts = $repository->all();

        return view(__DIR__ . '/blog_index.view.php', posts: $posts);
    }

    #[Get('/blog/{slug}')]
    public function show(string $slug, BlogRepository $repository): View
    {
        $post = $repository->find($slug);

        return view(__DIR__ . '/blog_show.view.php', post: $post);
    }

    #[Get('/rss')]
    public function rss(
        Cache $cache,
        ViewRenderer $renderer,
        BlogRepository $repository
    ): Response {
        $xml = $cache->resolve(
            key: 'rss',
            cache: fn () => $renderer->render(
                view(__DIR__ . '/rss.view.php', posts: $repository->all(loadContent: true)),
            ),
            expiresAt: new DateTimeImmutable('+1 hour')
        );

        return (new Ok($xml))
            ->addHeader('Content-Type', 'application/xml;charset=UTF-8');
    }
}