<?php

namespace App\Front\Docs;

use App\Chapters\ChapterRepository;
use Generator;
use Tempest\Http\DataProvider;

final readonly class DocsDataProvider implements DataProvider
{
    public function __construct(
        private ChapterRepository $chapterRepository
    ) {}

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