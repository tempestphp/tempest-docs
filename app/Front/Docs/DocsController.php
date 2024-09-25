<?php

declare(strict_types=1);

namespace App\Front\Docs;

use App\Chapters\ChapterRepository;
use Tempest\Http\Get;
use Tempest\Http\Responses\Redirect;
use Tempest\Http\StaticPage;
use Tempest\View\View;
use function Tempest\uri;

final readonly class DocsController
{
    #[Get('/docs')]
    public function docsIndex(): Redirect
    {
        return $this->frameworkIndex();
    }

    #[Get('/framework/01-getting-started')]
    public function frameworkIndex(): Redirect
    {
        return new Redirect(uri([self::class, '__invoke'], category: 'framework', slug: '01-getting-started'));
    }

    #[Get('/console/01-getting-started')]
    public function consoleIndex(): Redirect
    {
        return new Redirect(uri([self::class, '__invoke'], category: 'console', slug: '01-getting-started'));
    }
    
    #[StaticPage(DocsDataProvider::class)]
    #[Get('/docs/{category}/{slug}')]
    public function __invoke(string $category, string $slug, ChapterRepository $chapterRepository): View
    {
        return new DocsView(
            chapterRepository: $chapterRepository,
            currentChapter: $chapterRepository->find($category, $slug),
        );
    }
}
