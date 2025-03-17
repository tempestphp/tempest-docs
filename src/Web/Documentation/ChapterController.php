<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use Tempest\Router\Exceptions\NotFoundException;
use Tempest\Router\Get;
use Tempest\Router\Response;
use Tempest\Router\Responses\NotFound;
use Tempest\Router\Responses\Redirect;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\Support\arr;
use function Tempest\Support\Str\before_first;
use function Tempest\uri;

final readonly class ChapterController
{
    #[Get('/docs/{path:.*}')]
    public function docsRedirect(string $path): Redirect
    {
        return new Redirect('/main/' . $path);
    }

    #[Get('/docs')]
    #[Get('/documentation')]
    public function index(): Redirect
    {
        $version = Version::default();
        $category = basename(arr(glob(__DIR__ . "/content/{$version->value}/*", flags: GLOB_ONLYDIR))->sort()->first());
        $slug = basename(arr(glob(__DIR__ . "/content/{$version->value}/{$category}/*.md"))->map(fn (string $path) => before_first(basename($path), '.'))->sort()->first());

        return new Redirect(uri(
            [self::class, '__invoke'],
            version: $version,
            category: $category,
            slug: $slug,
        ));
    }

    #[StaticPage(DocumentationDataProvider::class)]
    #[Get('/{version}/{category}/{slug}')]
    public function __invoke(string $version, string $category, string $slug, ChapterRepository $chapterRepository): View|Response
    {
        if (is_null($version = Version::tryFrom($version))) {
            return new NotFound();
        }

        $currentChapter = $chapterRepository->find($version, $category, $slug);

        if (! $currentChapter) {
            return new NotFound();
        }

        return new ChapterView(
            version: $version,
            chapterRepository: $chapterRepository,
            currentChapter: $currentChapter,
        );
    }
}
