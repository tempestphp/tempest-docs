<?php

namespace App\Front\Docs;

use Generator;
use Tempest\Router\DataProvider;

final readonly class DocsDataProvider implements DataProvider
{
    public function __construct(
        private DocsRepository $chapterRepository,
    ) {
    }

    #[\Override]
    public function provide(): Generator
    {
        foreach ($this->chapterRepository->all() as $chapter) {
            yield [
                'category' => $chapter->category,
                'slug' => $chapter->slug,
            ];
        }
    }
}
