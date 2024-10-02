<?php

namespace App\Front\Blog;

use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\Http\Responses\Redirect;
use Tempest\View\View;
use function Tempest\view;

final readonly class BlogController
{
    #[Get('/blog')]
    public function index(BlogRepository $repository): Response
    {
        return new Redirect('/');
        $posts = $repository->all();

        return view(__DIR__ . '/blog_index.view.php', posts: $posts);
    }

    #[Get('/blog/{slug}')]
    public function show(string $slug, BlogRepository $repository): Response
    {
        return new Redirect('/');
        $post = $repository->find($slug);

        return view(__DIR__ . '/blog_show.view.php', post: $post);
    }
}