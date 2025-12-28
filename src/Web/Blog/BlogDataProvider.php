<?php

declare(strict_types=1);

namespace App\Web\Blog;

use Generator;
use Override;
use Tempest\Router\DataProvider;

final readonly class BlogDataProvider implements DataProvider
{
    public function __construct(
        private BlogRepository $repository,
    ) {}

    #[Override]
    public function provide(): Generator
    {
        foreach ($this->repository->all() as $post) {
            yield ['slug' => $post->slug];
        }
    }
}
