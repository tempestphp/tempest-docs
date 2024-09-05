<?php

declare(strict_types=1);

namespace App\Front\Docs;

use App\Chapters\ChapterRepository;
use Tempest\Http\Get;
use Tempest\Http\StaticPage;
use Tempest\View\View;

final readonly class DocsController
{
    #[StaticPage(DocsDataProvider::class)]
    #[Get('/{category}/{slug}')]
    public function __invoke(string $category, string $slug, ChapterRepository $chapterRepository): View
    {
        return new DocsView(
            chapterRepository: $chapterRepository,
            currentChapter: $chapterRepository->find($category, $slug),
        );
    }
}
