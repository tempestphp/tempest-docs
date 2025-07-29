<?php

namespace App\Web\Documentation;

use Generator;
use Tempest\Router\DataProvider;

final readonly class DefaultDocumentationDataProvider implements DataProvider
{
    public function __construct(
        private ChapterRepository $chapterRepository,
    ) {}

    #[\Override]
    public function provide(): Generator
    {
        /** @var Chapter $chapter */
        foreach ($this->chapterRepository->all(Version::default()) as $chapter) {
            yield [
                'version' => $chapter->version->value,
                'category' => $chapter->category,
                'slug' => $chapter->slug,
            ];
        }
    }
}
