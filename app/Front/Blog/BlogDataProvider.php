<?php

namespace App\Front\Blog;

use Generator;
use Tempest\Router\DataProvider;

final readonly class BlogDataProvider implements DataProvider
{
    public function __construct(
        private BlogRepository $repository,
    ) {}

    public function provide(): Generator
    {
        foreach ($this->repository->all() as $post) {
            yield ['slug' => $post->slug];
        }
    }
}