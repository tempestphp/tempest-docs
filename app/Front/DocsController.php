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
        return response()->redirect(uri([self::class, 'show'], slug: '01-getting-started'));
    }

    #[Get('/{slug}')]
    public function show(
        string $slug,
        ChapterRepository $chapters,
    ): View
    {
        return new DocsView(
            chapters: $chapters->all(),
            currentChapter: $chapters->find($slug),
        );
    }
}

