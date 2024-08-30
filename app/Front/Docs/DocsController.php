<?php

declare(strict_types=1);

namespace App\Front\Docs;

use App\Chapters\ChapterRepository;
use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\Http\Responses\Redirect;
use Tempest\Http\StaticPage;
use Tempest\View\View;
use function Tempest\uri;

final readonly class DocsController
{
    #[Get('/')]
    public function home(): Response
    {
        return new Redirect(uri([self::class, 'show'], category: 'framework', slug: '01-getting-started'));
    }

    #[StaticPage(DocsDataProvider::class)]
    #[Get('/{category}/{slug}')]
    public function show(string $category, string $slug, ChapterRepository $chapterRepository): View
    {
        return new DocsView(
            chapterRepository: $chapterRepository,
            currentChapter: $chapterRepository->find($category, $slug),
        );
    }
}
