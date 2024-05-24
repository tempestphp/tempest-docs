<?php

declare(strict_types=1);

namespace App\Front;

use App\Chapters\ChapterRepository;
use Tempest\Http\Get;
use Tempest\Http\Response;

use Tempest\Http\Responses\Redirect;
use function Tempest\uri;

use Tempest\View\View;

final readonly class DocsController
{
    #[Get('/')]
    public function home(): Response
    {
        return new Redirect(uri([self::class, 'show'], category: 'console', slug: '01-getting-started'));
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
