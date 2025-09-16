<?php

namespace App\Web\Documentation;

use Override;
use Generator;
use Tempest\Router\DataProvider;

final readonly class DocumentationDataProvider implements DataProvider
{
    public function __construct(
        private ChapterRepository $chapterRepository,
    ) {}

    #[Override]
    public function provide(): Generator
    {
        foreach (Version::collect() as $version) {
            /** @var Chapter $chapter */
            foreach ($this->chapterRepository->all($version) as $chapter) {
                yield [
                    'version' => $chapter->version->value,
                    'category' => $chapter->category,
                    'slug' => $chapter->slug,
                ];
            }
        }
    }
}
