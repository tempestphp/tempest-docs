<?php

declare(strict_types=1);

namespace App\Front\Docs;

use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Redirect;
use Tempest\Http\StaticPage;
use Tempest\View\View;
use function Tempest\uri;

final readonly class DocsController
{
    #[Get('/docs')]
    #[Get('/docs/framework')]
    #[Get('/framework/01-getting-started')]
    public function redirect(): Redirect
    {
        return new Redirect(uri([self::class, '__invoke'], category: 'framework', slug: 'getting-started'));
    }

    #[Get('/console/01-getting-started')]
    public function consoleIndex(): Redirect
    {
        return new Redirect(uri([self::class, '__invoke'], category: 'console', slug: 'getting-started'));
    }

    #[StaticPage(DocsDataProvider::class)]
    #[Get('/docs/{category}/{slug}')]
    public function __invoke(string $category, string $slug, DocsRepository $chapterRepository): View|Response
    {
        $currentChapter = $chapterRepository->find($category, $slug);

        if (! $currentChapter) {
            return new NotFound();
        }

        return new DocsView(
            chapterRepository: $chapterRepository,
            currentChapter: $currentChapter,
        );
    }
}
