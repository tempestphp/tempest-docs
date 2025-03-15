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

use function Tempest\Support\Arr\last;
use function Tempest\Support\Arr\map;
use function Tempest\Support\Str\before_first;
use function Tempest\uri;

final readonly class ChapterController
{
    #[Get('/docs')]
    #[Get('/documentation')]
    public function index(): Redirect
    {
        $version = last(map(glob(__DIR__ . '/content/*', flags: GLOB_ONLYDIR), fn (string $directory) => basename($directory)));

        return new Redirect(uri(
            [self::class, '__invoke'],
            version: $version,
            category: 'framework',
            slug: 'getting-started',
        ));
    }

    #[StaticPage(DocumentationDataProvider::class)]
    #[Get('/{version}/{category}/{slug}')]
    public function __invoke(string $version, string $category, string $slug, ChapterRepository $chapterRepository): View|Response
    {
        if (is_null($version = Version::tryFrom($version))) {
            throw new NotFoundException();
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
