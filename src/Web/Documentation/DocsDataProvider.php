<?php

namespace Tempest\Web\Documentation;

use Generator;
use Tempest\Router\DataProvider;

final readonly class DocsDataProvider implements DataProvider
{
    public function __construct(
        private ChapterRepository $chapterRepository,
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
