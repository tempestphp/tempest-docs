<?php

declare(strict_types=1);

namespace App\Front;

use App\Chapters\ChapterRepository;
use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\View\View;
use function Tempest\response;
use function Tempest\uri;

final readonly class DocsController
{
    #[Get('/')]
    public function home(): Response
    {
        return response()->redirect(uri([self::class, 'show'], category: 'web', slug: '01-getting-started'));
    }

    #[Get('/{category}/{slug}')]
    public function show(string $category, string $slug, ChapterRepository $chapterRepository): View
    {
        return new DocsView(
            chapters: $chapterRepository->all(),
            currentChapter: $chapterRepository->find($category, $slug),
            chapterRepository: $chapterRepository,
        );
    }
}

