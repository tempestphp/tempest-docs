<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use RuntimeException;
use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Support\Str\ImmutableString;
use Tempest\View\View;

use function Tempest\Support\arr;
use function Tempest\Support\Str\before_first;
use function Tempest\Router\uri;

final readonly class DocumentationController
{
    #[Get('/current/{path:.*}')]
    #[Get('/main/{path:.*}')]
    #[Get('/docs/{path:.*}')]
    public function redirect(string $path): Redirect
    {
        return new Redirect(sprintf('/%s/%s', Version::default()->getUrlSegment(), $path));
    }

    #[Get('/documentation')]
    #[Get('/docs')]
    #[Get('/{version}')]
    public function index(?string $version): Redirect
    {
        $version = Version::tryFromString($version);

        $category = arr(glob(__DIR__ . "/content/{$version->getUrlSegment()}/*", flags: GLOB_ONLYDIR))
            ->tap(fn (ImmutableArray $files) => $files->isEmpty() ? throw new RuntimeException('Documentation has not been fetched. Run `tempest docs:pull`.') : null)
            ->sort()
            ->mapFirstTo(ImmutableString::class)
            ->basename()
            ->toString();

        $slug = arr(glob(__DIR__ . "/content/{$version->getUrlSegment()}/{$category}/*.md"))
            ->map(fn (string $path) => before_first(basename($path), '.'))
            ->sort()
            ->mapFirstTo(ImmutableString::class)
            ->basename()
            ->toString();

        return new Redirect(uri(
            [self::class, '__invoke'],
            version: $version,
            category: str_replace('0-', '', $category),
            slug: str_replace('01-', '', $slug),
        ));
    }

    #[StaticPage(DocumentationDataProvider::class)]
    #[Get('/{version}/{category}/{slug}')]
    public function __invoke(string $version, string $category, string $slug, ChapterRepository $chapterRepository): View|Response
    {
        if (is_null($version = Version::tryFromString($version))) {
            return new NotFound();
        }

        return $this->chapterView($version, $category, $slug, $chapterRepository) ?? new Redirect(uri(self::class, 'index'));
    }

    private function chapterView(Version $version, string $category, string $slug, ChapterRepository $chapterRepository): ?ChapterView
    {
        $currentChapter = $chapterRepository->find($version, $category, $slug);

        if (! $currentChapter || $currentChapter->hidden) {
            return null;
        }

        return new ChapterView(
            version: $version,
            chapterRepository: $chapterRepository,
            currentChapter: $currentChapter,
        );
    }
}
