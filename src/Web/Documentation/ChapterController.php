<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\Support\Str\ImmutableString;
use Tempest\View\View;

use function Tempest\Support\arr;
use function Tempest\Support\Str\before_first;
use function Tempest\uri;

final readonly class ChapterController
{
    #[Get('/docs/{path:.*}')]
    public function docsRedirect(string $path): Redirect
    {
        return new Redirect(sprintf('/%s/%s', Version::default()->value, $path));
    }

    #[Get('/docs')]
    #[Get('/documentation')]
    #[Get('/main/framework/getting-started')]
    public function index(): Redirect
    {
        $version = Version::default();

        $category = arr(glob(__DIR__ . "/content/{$version->value}/*", flags: GLOB_ONLYDIR))
            ->sort()
            ->mapFirstTo(ImmutableString::class)
            ->basename()
            ->toString();

        $slug = arr(glob(__DIR__ . "/content/{$version->value}/{$category}/*.md"))
            ->map(fn (string $path) => before_first(basename($path), '.'))
            ->sort()
            ->mapFirstTo(ImmutableString::class)
            ->basename()
            ->toString();

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
        if (is_null($version = Version::tryFromString($version))) {
            return new NotFound();
        }

        $currentChapter = $chapterRepository->find($version, $category, $slug);

        if (! $currentChapter || $currentChapter->hidden) {
            return new NotFound();
        }

        return new ChapterView(
            version: $version,
            chapterRepository: $chapterRepository,
            currentChapter: $currentChapter,
        );
    }
}
